import redis
import json
import time
import smtplib
import threading
import sys

r_server = redis.Redis('192.168.99.100')
FROM = "x@y.com"

def main():

	while True:
		print("Waking up")
		for key in r_server.scan_iter(match='NOTIF*'):

			obj = json.loads(r_server.get(key))

			if obj["status"]=="ADDED":
				obj["status"]="PROCESSING"
				r_server.set("NOTIFICATION:"+obj["uuid"],json.dumps(obj));

				if "PUSH_SUBSCRIBE" in obj["channels"]:
					t = threading.Thread(target=notify_ps, args = (obj,))
					t.daemon = True
					t.start()

				if "EMAIL" in obj["channels"]:
					t = threading.Thread(target=notify_email, args = (obj,))
					t.daemon = True
					t.start()
		time.sleep(2)


def save_to_redis(key, obj):
   print("Saving in redis")
   r_server.lpush(key["email"],obj["message"])
   r_server.incr("counter_ps")

def send_email(key, obj):
   try:
       r_server.incr("counter_email")
       server = smtplib.SMTP('localhost')
       server.sendmail(FROM, key["email"], obj["message"]["message"])
       server.quit()
   except Exception:
    r_server.set(obj["uuid"]+":FAILURE:"+key["email"],key["email"]);

def notify_ps(obj):
    for key in obj["subscribers"]:
        t = threading.Thread(target=save_to_redis, args = (key, obj,))
        t.daemon = True
        t.start()

    obj["status"]="COMPLETED"
    r_server.set("NOTIFICATION:"+obj["uuid"],json.dumps(obj));

def notify_email(obj):
    for key in obj["subscribers"]:
       t = threading.Thread(target=send_email, args = (key, obj,))
       t.daemon = True
       t.start()

    obj["status"]="COMPLETED"
    r_server.set("NOTIFICATION:"+obj["uuid"],json.dumps(obj));

if __name__ == "__main__":
    main()
	