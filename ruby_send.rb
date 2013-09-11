require 'rest-client'

LOG_DIR   ='c:\\inetpub\\wwwroot\\log\\'
##RESTCLIENT_LOG=LOG_DIR+'send_sf.txt'   #--$stdout
RestClient.log=LOG_DIR+'send_sf.txt'   #--$stdout

sessionId = "00DG0000000CkUd!AQ0AQMJuKV_pdLZnWukptDBZi.OK9Cv1u9s8Djf8TIyVzgwRWy4ue5rVqZqManjWbuOVDtKXjCYs_pzUH9pDe3s4F7x4h_va"
send_url='https://c.na11.visual.force.com/apex/test'
ppt_state_str="done;sliders_cnt=63"
send_res = RestClient.post(
    send_url,
    {:state => ppt_state_str},
    {:cookies => {:sessionId => sessionId}}
)
RestClient.log << send_res.code
RestClient.log << send_res.body