require "azure"
Azure.config.storage_account_name = "sfclmstorage"
Azure.config.storage_access_key = "JtyoTzjNZHBAWVuDQNpN3LaJcPWjc51bMvRT7xG4hZA7vpT6qZTjKoJUU6z8sY2+zsvsGe7j05OupWmumABz5A=="

azure_table_service = Azure::TableService.new
query = { :filter => "org_id eq '00D20000000Caz4EAC'" }
result, token = azure_table_service.query_entities("sftokens", query)

print result
print token