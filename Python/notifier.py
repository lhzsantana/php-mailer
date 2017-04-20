import redis
import json
import time
import smtplib
import threading

r_server = redis.Redis('192.168.99.100')

def save_to_redis(key, obj):
   r_server.lpush(key["email"],obj["message"])
   r_server.incr("counter_ps")

def send_email(key, obj):
   r_server.incr("counter_email")

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

while True:
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

