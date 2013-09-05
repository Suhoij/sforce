  require 'win32ole'
  require 'fileutils'
  require 'logger'

  UPLOAD_DIR        ='c:/inetpub/wwwroot/upload/'
  LOG_DIR           ='c:\\inetpub\\wwwroot\\log\\'
  
  log=Logger.new(LOG_DIR+'logextractor.log')
  log.level = Logger::INFO

  ppt = WIN32OLE.new('PowerPoint.Application')
  ppt.visible = true
  ## system "start c:/inetpub/wwwroot/upload/Abbott.pptx"
  presentation = ppt.Presentations.Open('c:/inetpub/wwwroot/upload/Abbott.pptx');
  #
  sliders_cnt=ppt.ActivePresentation.Slides.Count()
  log.info("SLIDERS_CNT :"+sliders_cnt.to_s)
  sleep 5
  sliders_dir = 'c:\\inetpub\\wwwroot\\preview\\ppt\\00DG0000000CkUdMAK\\a01G000000DTkc0IAD\\sliders\\' 
  for i in 1..sliders_cnt
  	ppt.ActivePresentation.Slides(i).Export(sliders_dir+"slide_#{i}.jpg", ".jpg", 1024,768)
  end
  ppt.ActivePresentation.Close()
  ppt.quit()

