class Numeric
  def duration
    rest, secs = self.divmod(60) # self is the time difference t2 - t1
    rest, mins = rest.divmod(60)
    days, hours = rest.divmod(24)

    # the above can be factored out as:
    # days, hours, mins, secs = self.duration_as_arr
    #
    # this is not so great, because it could include zero values:
    # self.duration_as_arr.zip ['Days','Hours','Minutes','Seconds']).flatten.join ' '

    result = []
    result << "#{days} Days" if days > 0
    result << "#{hours} Hours" if hours > 0
    result << "#{mins} Minutes" if mins > 0
    result << "#{secs} Seconds" if secs > 0
    return result.join(' ')
  end
end
#----------------------Convertor--------------------------------------
class ConvertorPPT_HTML
  ORG_APP_DELIM = '_'
  CONVERTOR_SDK_DIR ='c:\\inetpub\\wwwroot\\files_for_redistribution\\'
  INPUT_DIR ='c:\\inetpub\\wwwroot\\input_ppt\\'
  OUTPUT_DIR ='c:\\inetpub\\wwwroot\\preview\\ppt\\' #--'c:\\inetpub\\wwwroot\\output_html\\ppts_html\\'
  PPT_DIR ='c:\\inetpub\\wwwroot\\preview\\ppt\\'
  PPT_PARAMS_FILE ='ppt_params.json'
  UPLOAD_DIR ='c:/inetpub/wwwroot/upload/'
  LOG_DIR ='c:\\inetpub\\wwwroot\\log\\'
  state = 'init'
  @@log=''
  @@org_id=''
  @@app_id=''
  #------------------------init------------------------
  def initialize
    require 'logger'
    require 'fileutils'
    require 'json'
    require 'rest-client'
    require 'win32ole'
    @state='init'
    @@log=Logger.new(LOG_DIR+'logconvertor.log')
    @@log.level = Logger::INFO
    ##--@@log=Logger.new(STDOUT)
    @@send_url='https://na11.salesforce.com/services/Soap/class/HelperClass'
    @@schema_url='http://soap.sforce.com/schemas/class/HelperClass'
    @@ppt_session_id='00DG0000000CkUd!AQ0AQGm6koOyXnC8wEqRnUPgNXCl2d14HDwKJmsOovS0QC0On9eIr7F0kijnFUJI0A9oi5I_ewziKnewkLpFDQQtv2kV6DYC'

  end

  #----------------------writeState--------------------
  def writeState(state, job, mes='')
    cur_state_file=Dir[LOG_DIR+'state_ppt.*'][0]
    File.delete(LOG_DIR+cur_state_file) unless cur_state_file.nil?
    stateHash = {
        "state" => state,
        "job" => job,
        "time" => Time.now.strftime("%d/%m/%H/%M/%S")
    }
    stateHash[:mes]=mes if not mes.empty?
    if job =~/convert|extract/
      stateHash[:org_id] = @@org_id
      stateHash[:app_id] = @@app_id
    end
    new_state_file = LOG_DIR+'state_ppt.'+job
    File.open(new_state_file, "w") do |f|
      f.write(stateHash.to_json)
    end
  end

  #------------------------convert---------------------
  def convert(f)
    #--%x(#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{OUTPUT_DIR}index.html)
    if (not @@org_id.empty?) and (not @@org_id.empty?)
      file_out_dir=OUTPUT_DIR+@@org_id+'\\'+@@app_id
    else
      file_out_dir=OUTPUT_DIR
    end
    file_name=File.basename(f, "*.*")
    writeState('work', 'convert')
    #org_id=f.split('_')[0]
    ##----for many presentation------------------
    #if ! org_id.nil?
    #   app_id=f.split('_')[1]
    #   if ! app_id.nil?
    #        if ! Dir.exist?(file_out_dir+org_id)
    #	          Dir.mkdir(file_out_dir+org_id)
    #	      end
    #        if ! Dir.exist?(file_out_dir+org_id+'\\'+app_id)
    #	          Dir.mkdir(file_out_dir+org_id+'\\'+app_id)
    #	      end
    #        file_out_dir=file_out_dir+"#{org_id}\\#{app_id}"  #---+file_name
    #    else
    #
    #       if ! Dir.exist?(file_out_dir+file_name)
    #	          Dir.mkdir(file_out_dir+file_name)
    #	          file_out_dir=file_out_dir+file_name
    #       end
    #   end
    #
    #end

    @state='convert'
    begin
      @@log.info("Start convert:  file:"+f)
      FileUtils.copy("#{UPLOAD_DIR}#{f}", "#{INPUT_DIR}#{f}")
      convert_cmd="#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{file_out_dir}\\index.html"
      @@log.info("Convert string:"+convert_cmd)
      %x(#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{file_out_dir}\\index.html)
      #File.rename("#{INPUT_DIR}#{f}","#{INPUT_DIR}#{file_name}"+".done")
      #File.rename("#{UPLOAD_DIR}#{f}","#{UPLOAD_DIR}#{file_name}"+".done")
      @state='done'
      time = Time.now
      @@log.info("Done convert file:"+f+"  time:"+time.inspect)
    rescue
      @@log.info("ERROR convert file:"+f)
      writeState('error', 'convert', f)
    end
  end

  #-----------------------extractor---------------------
  def extractSliders (f)
    begin
      writeState('work', 'extract')

      log=Logger.new(LOG_DIR+'logextractor.log')
      log.level = Logger::INFO
      begin
        ppt = WIN32OLE.open('PowerPoint.Application')
      rescue
        ppt = WIN32OLE.new('PowerPoint.Application')
      end

      #-----ppt.visible = false
      presentation = ppt.Presentations.Open(INPUT_DIR+f);
      @@sliders_cnt=ppt.ActivePresentation.Slides.Count()
      log.info(" ExtractSliders org_id=#{@@org_id} app_id=#{@@app_id} SLIDERS_CNT="+@@sliders_cnt.to_s+" time:"+Time.now.strftime("%d/%m/%H/%M/%S"))
      sleep 1 #---wait while ppt build sliders list
      sliders_dir = OUTPUT_DIR+@@org_id+"\\"+@@app_id+"\\sliders\\"
      #---clear old files in dir sliders
      FileUtils.rm_rf(sliders_dir)
      if !Dir.exist?(OUTPUT_DIR+@@org_id+"\\"+@@app_id+"\\sliders")
        Dir.mkdir(OUTPUT_DIR+@@org_id+"\\"+@@app_id+"\\sliders")
      end

      for i in 1..@@sliders_cnt
        ppt.ActivePresentation.Slides(i).Export(sliders_dir+"slide_#{i}.jpg", ".jpg", 2048, 1536) #--1024 768
      end
      ppt.ActivePresentation.Close()
      ppt.quit()
    rescue RuntimeError => error
      log.info('Extract slider ERROR '+error.inspect)
      writeState('error', 'extract', error.inspect)
    ensure
      log.info("Extract slider DONE! org_id=#{@@org_id} app_id=#{@@app_id}")
      writeState('work', 'extract-done-'+@@sliders_cnt.to_s, 'done') if !@@sliders_cnt.nil?
    end


  end

  #------------------------extract ppt params----------
  def extractPptParams
    begin
      file_params=PPT_DIR+@@org_id+'/'+@@app_id+'/'+PPT_PARAMS_FILE
      if not File.exist?(file_params)
        @@log.info('Error file-PptParams NO FILE PARAMS')
        writeState('error', 'extract-Ppt-Params', "no params file org_id=#{@@org_id} app_id=#{@@app_id}")
        return
      end
      writeState('work', 'extract-soap')
      content=File.read(file_params)
      unless content.empty?
        require 'json'
        #--extract json string from content
        ppt_params=JSON.parse(content)
      end
      @@send_url = ppt_params['send_url'] if not ppt_params['schema_url'].empty?
      @@schema_url = ppt_params['schema_url'] if not ppt_params['schema_url'].empty?
      @@ppt_session_id = ppt_params['ppt_session_id'] if not ppt_params['ppt_session_id'].empty?
    rescue RuntimeError => error
      @@log.info('Extract PptParams ERROR '+error.inspect)
      writeState('error', 'extract-soap', error.inspect)
    end
  end

  #-----------------------getSoapXml--------------------
  def getSoapXml
    s_id = @@ppt_session_id
    soap_url= @@schema_url #--http://soap.sforce.com/schemas/class/HelperClass
    cur_sliders_cnt=@@sliders_cnt
    cur_app_id=@@app_id
    tpl=%{
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:hel="#{soap_url}">
   <soapenv:Header>
      <hel:AllowFieldTruncationHeader>
         <hel:allowFieldTruncation>0</hel:allowFieldTruncation>
      </hel:AllowFieldTruncationHeader>

      <hel:CallOptions>
         <hel:client>27.0</hel:client>
      </hel:CallOptions>
      <hel:SessionHeader>
         <hel:sessionId>#{s_id}</hel:sessionId>
      </hel:SessionHeader>
   </soapenv:Header>
   <soapenv:Body>
      <hel:presentationUploaded>
         <hel:sliders_cnt>#{cur_sliders_cnt}</hel:sliders_cnt>
         <hel:app_id>#{cur_app_id}</hel:app_id>
      </hel:presentationUploaded>
   </soapenv:Body>
</soapenv:Envelope>
}
  end

  #------------------------send state-------------------
  def sendState (file_name)
    begin
      extractPptParams()

      writeState('work', 'send-state', 'start')
      RestClient.log=LOG_DIR+'send_sf.txt' #--$stdout
      #send_url='https://c.na11.visual.force.com/apex/test'
      post_data=getSoapXml()
      #---ENV['PERL_LWP_SSL_VERIFY_HOSTNAME']=0;
      send_res = RestClient.post(
          @@send_url,
          post_data, {
          "content-type" => "text/xml;charset=\"utf-8\"",
          "Accept" => "text/xml",
          "Cache-Control" => "no-cashe",
          "Pragma" => "no-cashe",
          "SOAPAction" => "\"Run\"",
          "Content-length" => post_data.size,
          "verify_ssl" => 0
      }
      )
      RestClient.log << send_res.code
      RestClient.log << send_res.body
      writeState('work', 'send-state', 'done')
    rescue RuntimeError => error
      @@log.info('SendState  ERROR '+error.inspect)
      writeState('error', 'send-state', error.inspect)
      File.rename("#{UPLOAD_DIR}#{file_name}", "#{UPLOAD_DIR}#{file_name}"+".error_send")
    end
  end

  #-----------------------exec_every_seconds---------
  def exec_every_seconds(seconds)
    last_tick = Time.now
    loop do
      sleep 0.1
      if Time.now - last_tick >= seconds
        yield
        last_tick = Time.now
      end
    end
  end

  #------------------------listen-----------------------
  def listen
    time = Time.now
    @@log.info("Start listen:  state:"+@state)
    while 1
      writeState('work', 'wait')
      next if @state=='convert'
      sleep 1
      #--read files upload_dir
      #--Dir.entries(UPLOAD_DIR).select {|f| !File.directory? f}
      files_to_convert=Dir[UPLOAD_DIR+"*.ppt"]+Dir[UPLOAD_DIR+"*.pptx"]
      pt_files=Dir[UPLOAD_DIR+"*pt_*.ppt"] +Dir[UPLOAD_DIR+"*pt_*.pptx"]
      files_to_convert=files_to_convert-pt_files
      #pt_files=files_to_convert.select {|i| File.basename(i) =~/pt_*\.pptx/}
      #if pt_files.size >0
      #   @@log.info("Have pt_ :")
      #   sleep 5
      #   next
      #end
      if files_to_convert.size == 0
        #@@log.close
        #abort
      else
        @@log.info("Files in dir:"+files_to_convert.size.to_s)
        file=files_to_convert.first
        file_name=File.basename(file, "*.*")
        if !file.nil?
          time = Time.now
          @@log.info("Convert file:"+file+"  time:"+time.inspect)
          #-----------------------------------get org_id,app_id from file name-----------
          @@org_id=file_name.split(ORG_APP_DELIM)[0]
          @@app_id=file_name.split(ORG_APP_DELIM)[1]
          @@org_id='1111111' if (@@org_id.nil?)
          @@app_id='1111111' if (@@app_id.nil?)
          #----------------------------------create dir(s)--------------------------------
          if !Dir.exist?(OUTPUT_DIR+@@org_id)
            Dir.mkdir(OUTPUT_DIR+@@org_id)
          end
          if !Dir.exist?(OUTPUT_DIR+@@org_id+"\\"+@@app_id)
            Dir.mkdir(OUTPUT_DIR+@@org_id+"\\"+@@app_id)
          end
          convert File.basename(file)
          extractSliders File.basename(file)
          sendState(file_name)
          #------------rename upload-input file-------------------------------------------
          if File.exist?("#{INPUT_DIR}#{file_name}")
            File.rename("#{INPUT_DIR}#{file_name}", "#{INPUT_DIR}#{file_name}"+".done")
          end
          if File.exist?("#{UPLOAD_DIR}#{file_name}")
            File.rename("#{UPLOAD_DIR}#{file_name}", "#{UPLOAD_DIR}#{file_name}"+".done")
          end
        end
      end

    end
  end #--lister
end #--class

#-------------------------main-----------------------
conv=ConvertorPPT_HTML.new
conv.listen