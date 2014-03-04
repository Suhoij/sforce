#-------------------commands-----------
#------deleteTable--------------------
#------createTokens--------------------
#------updateToken--------------------
require "securerandom"
require "azure"

Azure.config.storage_account_name = "sfclmstorage"
Azure.config.storage_access_key = "JtyoTzjNZHBAWVuDQNpN3LaJcPWjc51bMvRT7xG4hZA7vpT6qZTjKoJUU6z8sY2+zsvsGe7j05OupWmumABz5A=="

SLIDERS_DIR ='c:/inetpub/wwwroot/preview/slide/'
PPT_DIR     ='c:/inetpub/wwwroot/preview/ppt/'

@azure_table_service = Azure::TableService.new
#------------------------------------methods--------------------------
def createSlideTokens
  tokens_cnt=0
  org_cnt=app_cnt=0
  bad_dir_arr = [".","..","index.php","ppt_params.json","ppt_params.php","sliders","1","2","css","data","scripts"]
  Dir.entries(PPT_DIR).each_with_index do |org_id_name,org_index|
    if File.directory?(PPT_DIR+org_id_name) and ( bad_dir_arr.include?(org_id_name)==false)
      org_cnt+=1
      app_cnt=0
      app_id_arr=Dir.entries(PPT_DIR+org_id_name)
      app_id_arr.each_with_index do |app_id_name,app_index|
        if File.directory?(PPT_DIR+"/"+org_id_name+"/"+app_id_name)  and ( bad_dir_arr.include?(app_id_name)==false)
          app_cnt+=1
          entity = {:PartitionKey =>org_id_name,:RowKey =>app_id_name,:token=>SecureRandom.hex(12) }
          @azure_table_service.insert_entity("sftokens", entity)
          tokens_cnt=tokens_cnt+org_cnt+app_cnt;
          p "Done:#{tokens_cnt}"
        end
      end
      p "-----------------"
    end
  end
  abort

end

def createOrgTokens
  org_cnt=0
  bad_dir_arr = [".","..","index.php","ppt_params.json","ppt_params.php","sliders","1","2","css","data","scripts"]
  Dir.entries(PPT_DIR).each_with_index do |org_id_name,org_index|
    if File.directory?(PPT_DIR+org_id_name) and ( bad_dir_arr.include?(org_id_name)==false)
      entity = {:PartitionKey =>org_id_name,:RowKey =>org_id_name,:token=>SecureRandom.hex(12) }
      @azure_table_service.insert_entity("orgtokens", entity)
      org_cnt+=1
    end
  end
  p "Done #{org_cnt} "
end
#-------------------main-------------------------------------
if ARGV[0] == "deleteTable"
  p "deleteTable..."
  @azure_table_service.delete_table("sftokens")
  abort
end

if ARGV[0] == "createTable"
  p "try createTable..."
  table_name = ARGV[1]
  if not table_name.nil?
     #azure_table_service.create_table("sftokens")
     @azure_table_service.create_table(table_name)
     p " created table "+table_name
  end
  abort
end

if ARGV[0] == "createTokens"
   createSlideTokens() if ARGV[1]=='slide'
   createOrgTokens()   if ARGV[1]=='org'
end

if ARGV[0] == "updateToken"
  org_id=ARGV[1]
  app_id=ARGV[2]
  token=SecureRandom.hex(12)
  result=Azure::Table::Entity.new
  #p result.to_json
  #result.properties.PartitionKey = org_id
  #result.properties.RowKey      = app_id
  #result.properties.token       = token
  entity = { :PartitionKey => org_id, :RowKey => app_id,:token=> token }
  result.properties = entity
  p "Start update: org_id=#{org_id}  app_id=#{app_id}";
  begin
  #azure_table_service.update_entity("sftokens", entity)
  @azure_table_service.update_entity("sftokens", result.properties)
  rescue RuntimeError => error
  p "Error update:"+error.inspect
  end
  p "New token is #{token}"
  abort
end

if ARGV[0] == "addToken"
  org_id=ARGV[1]
  app_id=ARGV[2]
  token=SecureRandom.hex(12);
  begin
  entity = { :token => token,:PartitionKey => org_id, :RowKey => app_id }
  @azure_table_service.insert_entity("sftokens", entity)
  rescue  RuntimeError => error
    p "Error update:"+error.inspect
  end
  p "done token:"+token
end
#query = { :filter => "org_id eq 'test org_id'" }
#query = { :filter => "PartitionKey eq '1'" }
#result, token = azure_table_service.query_entities("sftokens", query)

#--entity = { "org_id" => "test org_id", :partition_key => "test-partition-key", :row_key => "1" }
#entity = { :org_id => "test org_id", :app_id =>"test app_it",:token=>"987654321",:PartitionKey => "test-org_id-app_id",:RowKey => "4" }



###print result.inspect

#print token.inspect
