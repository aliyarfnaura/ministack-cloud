import boto3

s3 = boto3.client("s3", endpoint_url="http://localhost:4566",
                   aws_access_key_id="AKIAA45930DF488F4557",
                   aws_secret_access_key="edf0c1902c49421d8090897abeb6682f8207fef5", region_name="us-east-1")

s3.put_object(Bucket="user-6-bucket", Key="test.txt", Body=b"Hello MiniStack!" * 1000)
print("File berhasil diupload!")