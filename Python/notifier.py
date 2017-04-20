import redis
import json
import smtplib

r_server = redis.Redis('192.168.99.100')

for key in r_server.scan_iter(match='NOTIF*'):
  obj = json.loads(r_server.get(key))

  if obj["status"]=="ADDED":

      obj["status"]="PROCESSING"
      r_server.set("NOTIFICATION:"+obj["uuid"],json.dumps(obj));

      if "PUSH_SUBSCRIBE" in obj["channels"]:
         for key in obj["subscribers"]:
            r_server.lpush(key["email"],obj["message"])
      if "EMAIL" in obj["channels"]:
         for key in obj["subscribers"]:
            print('a')
      obj["status"]="COMPLETED"
      r_server.set("NOTIFICATION:"+obj["uuid"],json.dumps(obj));
