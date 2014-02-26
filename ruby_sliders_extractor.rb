  require 'win32ole'
  require 'fileutils'
  require 'logger'

  UPLOAD_DIR        ='c:/work/salesforce/test/'  #  'c:/inetpub/wwwroot/upload/'
  LOG_DIR           ='c:/work/salesforce/test/'  #'c:\\inetpub\\wwwroot\\log\\'
  SLIDERS_DIR       ='c:\\work\\salesforce\\test\\sliders\\'  #'c:\\inetpub\\wwwroot\\preview\\ppt\\00DG0000000CkUdMAK\\a01G000000DTkc0IAD\\sliders\\'
  #PPT_FILE_NAME     ='00D20000000Caz4EAC_a0E2000000odSfIEAU_ANI-Chicago-December-visit---EK.pptx'
  PPT_FILE_NAME     ='vertical.ppt'


  log=Logger.new(LOG_DIR+'logextractor.log')
  log.level = Logger::INFO

  ppt = WIN32OLE.new('PowerPoint.Application')
  ppt.visible = true
  ## system "start c:/inetpub/wwwroot/upload/Abbott.pptx"
  presentation = ppt.Presentations.Open(UPLOAD_DIR+PPT_FILE_NAME);
  #
  sliders_cnt=ppt.ActivePresentation.Slides.Count()
  log.info("SLIDERS_CNT :"+sliders_cnt.to_s)
  sleep 1

  for i in 1..sliders_cnt
    #ppt.ActivePresentation.Slides(i).Export(SLIDERS_DIR+"slide_#{i}.jpg", ".jpg", 2048, 1536)
    ppt.ActivePresentation.Slides(i).Export("c:\\work\\salesforce\\test\\sliders\\slide_#{i}.jpg", ".jpg")
    log.info(" slide width=#{ppt.ActivePresentation.PageSetup.SlideWidth}  slide height=#{ppt.ActivePresentation.PageSetup.SlideHeight}")
  end
  ppt.ActivePresentation.Close()
  ppt.quit()

