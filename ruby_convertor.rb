class Numeric
   def duration
     rest, secs = self.divmod( 60 )  # self is the time difference t2 - t1
     rest, mins = rest.divmod( 60 )
     days, hours = rest.divmod( 24 )

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

 CONVERTOR_SDK_DIR ='c:\\inetpub\\wwwroot\\files_for_redistribution\\'
 INPUT_DIR         ='c:\\inetpub\\wwwroot\\input_ppt\\'
 OUTPUT_DIR        ='c:\\inetpub\\wwwroot\\preview\\ppt\\'   #--'c:\\inetpub\\wwwroot\\output_html\\ppts_html\\'
 UPLOAD_DIR        ='c:/inetpub/wwwroot/upload/'
 LOG_DIR           ='c:\\inetpub\\wwwroot\\log\\'
 state = 'init'
 @@log=''
 @@org_id=''
 @@app_id=''
#------------------------init------------------------
def initialize
  require 'logger'
  require 'fileutils'
  @state='init'
  @@log=Logger.new(LOG_DIR+'logconvertor.log')
  @@log.level = Logger::INFO
  ##--@@log=Logger.new(STDOUT)
end
#------------------------convert---------------------
def convert(f)
	#--%x(#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{OUTPUT_DIR}index.html) 
	file_out_dir=OUTPUT_DIR
	file_name=File.basename(f,"*.*")
	org_id=f.split('_')[0]	
	#----for many presentation------------------
	if ! org_id.nil?
	   app_id=f.split('_')[1]
	   if ! app_id.nil?
	        if ! Dir.exist?(file_out_dir+org_id)
		     Dir.mkdir(file_out_dir+org_id)
		end
	        if ! Dir.exist?(file_out_dir+org_id+'\\'+app_id)
		     Dir.mkdir(file_out_dir+org_id+'\\'+app_id)
		end
	        file_out_dir=file_out_dir+"#{org_id}\\#{app_id}"  #---+file_name
	    else
	      	
	       if ! Dir.exist?(file_out_dir+file_name)
		       Dir.mkdir(file_out_dir+file_name)
		       file_out_dir=file_out_dir+file_name
	       end 
	   end
	   
	 #code
	end
	

	@state='convert'
	begin
	  @@log.info("Start convert:  file:"+f)
          FileUtils.copy("#{UPLOAD_DIR}#{f}","#{INPUT_DIR}#{f}")
	  convert_cmd="#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{file_out_dir}\\index.html"
	  @@log.info("Convert string:"+convert_cmd)
	  %x(#{CONVERTOR_SDK_DIR}ppt2html5.exe /i:#{INPUT_DIR}#{f} /o:#{file_out_dir}\\index.html)
	  File.rename("#{INPUT_DIR}#{f}","#{INPUT_DIR}#{file_name}"+".done")
	  File.rename("#{UPLOAD_DIR}#{f}","#{UPLOAD_DIR}#{file_name}"+".done")
	  @state='done' 
	  time = Time.now
 	  @@log.info("Done convert file:"+f+"  time:"+time.inspect)
        rescue	 
 	  @@log.info("ERROR convert file:"+f)
	end
end
#------------------------listen-----------------------
def listen
     time = Time.now
     @@log.info("Start listen:  state:"+@state)
     while 1
        next if @state=='convert'
  	    #--read files upload_dir
	    #--Dir.entries(UPLOAD_DIR).select {|f| !File.directory? f}
	    files_to_convert=Dir[UPLOAD_DIR+"*.ppt"]+Dir[UPLOAD_DIR+"*.pptx"]
	    @@log.info("Files in dir:"+files_to_convert.size.to_s )
	    if files_to_convert.size == 0
		    @@log.close
		    abort
	    else
	        file=files_to_convert.first
            if !file.nil?
	            time = Time.now
	            @@log.info("Convert file:"+file+"  time:"+time.inspect)
	            convert File.basename(file)
           end
	    end
  	
     end
end #--lister
end #--class

#-------------------------main-----------------------
conv=ConvertorPPT_HTML.new
conv.listen