require 'rest-client'

LOG_DIR   ='c:\\inetpub\\wwwroot\\log\\'
PPT_DIR   ='c:\\inetpub\\wwwroot\\preview\\ppt\\'
PPT_PARAMS_FILE   ='ppt_params.json'
RESTCLIENT_LOG=$stdout   #--$stdout
#RestClient.log=LOG_DIR+'send_sf.txt'   #--$stdout
#
#sessionId = "00DG0000000CkUd!AQ0AQMJuKV_pdLZnWukptDBZi.OK9Cv1u9s8Djf8TIyVzgwRWy4ue5rVqZqManjWbuOVDtKXjCYs_pzUH9pDe3s4F7x4h_va"
#send_url='https://c.na11.visual.force.com/apex/test'
#ppt_state_str="done;sliders_cnt=63"
#send_res = RestClient.post(
#    send_url,
#    {:state => ppt_state_str},
#    {:cookies => {:sessionId => sessionId}}
#)
#RestClient.log << send_res.code
#RestClient.log << send_res.body
class SFSender
  def initialize
    require 'logger'
    @@log=Logger.new(LOG_DIR+'logsend.log')
    @@log.level = Logger::INFO
    @@send_url='https://na11.salesforce.com/services/Soap/class/HelperClass'
    @@soap_url='http://soap.sforce.com/schemas/class/HelperClass'
    @@ppt_session_id='00DG0000000CkUd!AQ0AQASO7j5Ae1w4EEXNK0SzBcDqAJUh7CiTdDb4Jlp_.XRM7qWOzPy4NBKokqrrOUMIj6295JXBP2ZdlgTqzUCCjXE6UNBJ'
    @@org_id='00DG0000000CkUdMAK'
    @@app_id='a01G000000BRpLFIA2'
    @@sliders_cnt=12
  end
#-----------------------getSoapXml--------------------
def getSoapXml
  s_id    = @@ppt_session_id
  soap_url= @@soap_url  #--http://soap.sforce.com/schemas/class/HelperClass
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
#------------------------extract ppt params----------
def extractPptParams
  content=File.read(PPT_DIR+@@org_id+'/'+@@app_id+'/'+PPT_PARAMS_FILE)
  unless content.empty?
    require 'json'
    #--extract json string from content
    ppt_params=JSON.parse(content)

  end
  @@send_url=ppt_params['sf_url']
  @@ppt_session_id=ppt_params['ppt_session_id']
rescue RuntimeError => error
  @@log.info('Extract PptParams ERROR '+error.inspect)
end
#------------------------send state-------------------
def sendState
  ####extractPptParams()
  require 'rest-client'
  RestClient.log=LOG_DIR+'send_sf.txt'   #--$stdout
                                         #send_url='https://c.na11.visual.force.com/apex/test'
  post_data=getSoapXml()
                                         #---ENV['PERL_LWP_SSL_VERIFY_HOSTNAME']=0;
  send_res = RestClient.post(
      @@send_url,
      post_data,{
      "content-type" => "text/xml;charset=\"utf-8\"",
      "Accept" =>"text/xml",
      "Cache-Control" => "no-cache",
      "Pragma" =>"no-cache",
      "SOAPAction" =>"\"Run\"",
      "Content-length" =>post_data.size,
      "verify_ssl" => 0,
      "verify_host" => 0

       }
  )
  RestClient.log << send_res.code
  RestClient.log << send_res.body
rescue RuntimeError => error
  @@log.info('Send  ERROR '+error.inspect)
end

end #--class

sf=SFSender.new
sf.sendState
