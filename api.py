#!flask/bin/python
from flask import Flask, jsonify, abort, request, make_response, url_for, send_from_directory
from flask_cors import CORS, cross_origin
from time import gmtime, strftime
import os
import json
import time
import uuid
import decimal
from os.path import expanduser

os.chdir("/var/www/html")
home = expanduser("~")
print home

app = Flask(__name__, static_url_path = "")
CORS(app)

@app.errorhandler(404)
def not_found(error):
    return make_response(jsonify( { 'error': 'Not found' } ), 404)

def url2json(data):
        output = {}
        data = data.split("&")
        for item in data:
                item = item.split("=")
                output[item[0]] = item[1]
        return output

#////////////////////////////////////////////////

@app.route('/add_product/<data>', methods = ['GET'])
def add_product(data):
        output = url2json(data)
        print output

	product = {
		"ID":str(uuid.uuid4()),
		"asking_price":float(output["price"]),
		"assembled":0,
		"desc":output["desc"],
		"funds":{
			"bottom":0,
			"current":float(output["funds"]),
			"earned":0,
			"invested":0,
			"units_sold":0
		},
		"minimum_units":int(output["units"]),
		"name":output["name"],
		"nick":output["name"].lower().replace(" ","_"),
		"parts":[],
	}

	directory = home+"/costs/products/"+product["nick"]
	if not os.path.exists(directory):
		os.makedirs(directory)
		with open(directory+"/product_info.json","w+") as f:
			f.write(json.dumps(product,indent=2))
		with open("product_index.lst","a+") as f:
			f.write(product["nick"]+"%|%"+product["ID"]+"\n")
		with open(directory+"/history.lst","w+") as f:
			newline = current_time()+" %|% INFO %|% Added Product %|% 0.00\n"
			f.write(newline)

	        return make_response(jsonify({"status":"success"}),200)
	else:
	        return make_response(jsonify({"status":"exists"}),400)

@app.route('/<product_id>/adjust_funds/<data>', methods = ['GET'])
def adjust_funds(product_id,data):
        output = url2json(data)
        print output

        with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                product = json.loads(f.read())

        product["funds"]["current"] = float(output["current"])
        product["funds"]["bottom"] = float(output["bottom"])
        product["funds"]["invested"] = float(output["invested"])
        product["funds"]["earned"] = float(output["earned"])
        product["funds"]["units_sold"] = int(output["units_sold"])
        product["assembled"] = int(output["units_assembled"])

        with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                f.write(json.dumps(product,sort_keys=True,indent=2))

        add_history(product_id,"ADJUST","Manual Funds Adjustment","0.00");

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/adjust_stock/<data>', methods = ['GET'])
def adjust_stock(product_id,data):
        output = url2json(data)

        product = {}

        for ID in output:
                onhand = output[ID]

                with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                        product = json.loads(f.read())

                index = 0
                found = 0
                for part in product["parts"]:
                        if part["ID"] == ID:
                                found = index
                        index+=1

                product["parts"][found]["onhand"] = int(onhand)
                print product["parts"][found]["onhand"]

                with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                        f.write(json.dumps(product,sort_keys=True,indent=2))

        add_history(product_id,"ADJUST","Manual Inventory Adjustment","0.00");

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/add_order/<data>', methods = ['GET'])
def add_order(product_id,data):
        output = url2json(data)

        for ID in output:
                print ID
                quantity = int(output[ID].split("-")[0])
                cost = float(output[ID].split("-")[1])

                with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                        product = json.loads(f.read())

                index = 0
                for part in product["parts"]:
                        if part["ID"] == ID:
                                print "MATCH"
                                product["parts"][index]["incoming"]+=int(quantity)
                                add_history(product_id,"ORDER",product["parts"][index]["name"]+" - "+str(quantity)+" PIECES","-"+price_short(cost));
                        index+=1

                product["funds"]["invested"]+=cost
                product["funds"]["current"]-=cost

                with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                        f.write(json.dumps(product,sort_keys=True,indent=2))

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/receive_order/<data>', methods = ['GET'])
def receive_order(product_id,data):
        output = url2json(data)

        for ID in output:
                print ID
                quantity = int(output[ID].split("-")[0])
                print quantity

                with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                        product = json.loads(f.read())

                index = 0
                for part in product["parts"]:
                        if part["ID"] == ID:
                                print "MATCH"
                                product["parts"][index]["onhand"]+=int(quantity)
                                product["parts"][index]["incoming"]-=int(quantity)
                                if quantity > 0:
                                        add_history(product_id,"RECEIVED",product["parts"][index]["name"]+" - "+str(quantity)+" PIECES","0.00");
                        index+=1

                with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                        f.write(json.dumps(product,sort_keys=True,indent=2))

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/add_part/<data>', methods = ['GET'])
def add_part(product_id,data):
	output = url2json(data)

	part = {}
	part["ID"] = str(uuid.uuid4())

	part["needed_per"] = int(output["needed_per"])
	part["order_quantity"] = int(output["order_quantity"])
	part["order_cost"] = float(output["order_cost"])
	part["shipping_cost"] = float(output["shipping_cost"])
	part["onhand"] = 0
	part["incoming"] = 0
	part["link"] = output["link"]
	part["name"] = output["name"]
	part["desc"] = output["desc"]

	with open(home+"/costs/products/"+product_id_to_nick(product_id)+"/product_info.json","r") as f:
		product = json.loads(f.read())

	print data
	print json.dumps(part,indent=2)

	product["parts"].append(part)

	with open(home+"/costs/products/"+product_id_to_nick(product_id)+"/product_info.json","w") as f:
		f.write(json.dumps(product,sort_keys=True,indent=2))

	return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/add_sale/<data>', methods = ['GET'])
def add_sale(product_id,data):
        output = url2json(data)

        with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                product = json.loads(f.read())

        product["funds"]["current"]+=float(output["profits"]);
        product["funds"]["earned"]+=float(output["profits"]);
        product["funds"]["units_sold"]+=float(output["units"]);
        product["assembled"]-=int(output["units"]);

        with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                f.write(json.dumps(product,sort_keys=True,indent=2))

        add_history(product_id,"SALE",product["name"]+" - "+output["units"]+" UNITS","+"+price_short(output["profits"]));

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/add_assembly/<data>', methods = ['GET'])
def add_assembly(product_id,data):
        output = url2json(data)

        with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                product = json.loads(f.read())

        product["assembled"]+=int(output["units"])

        add_history(product_id,"ASSEMBLED",product["name"]+" - "+str(output["units"])+" UNITS","0.00");

        index = 0
        while index < len(product["parts"]):
		product["parts"][index]["onhand"] = int(product["parts"][index]["onhand"]) - (int(product["parts"][index]["needed_per"])*int(output["units"]))

                print "ONHAND:"
                print product["parts"][index]["onhand"]
                print "NEEDED:"
                print int(product["parts"][index]["needed_per"])
                index+=1

        with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                f.write(json.dumps(product,sort_keys=True,indent=2))

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/add_funds/<data>', methods = ['GET'])
def add_funds(product_id,data):
        output = url2json(data)

        with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                product = json.loads(f.read())

        product["funds"]["current"]+=float(output["funds"]);

        if float(output["funds"]) > 0:
                add_history(product_id,"INCOME",output["reason"],"+"+price_short(output["funds"]));
        elif float(output["funds"]) < 0:
                add_history(product_id,"EXPENDITURE",output["reason"],price_short(output["funds"]));

        with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                f.write(json.dumps(product,sort_keys=True,indent=2))

        return make_response(jsonify({"status":"success"}),200)

@app.route('/<product_id>/remove_part/<part_id>', methods = ['GET'])
def remove_part(product_id,part_id):
        with open(home+"/costs/products/"+product_id+"/product_info.json","r") as f:
                product = json.loads(f.read())

        part_found = False
        part_index = 99999

        index = 0
        for item in product["parts"]:
                if item["ID"] == part_id:
                        print "MATCH!"
                        part_index = index
                        part_found = True
                        print part_index
                index+=1

        if part_found == True:
                del product["parts"][part_index]
                print json.dumps(product,sort_keys=True,indent=2)

                with open(home+"/costs/products/"+product_id+"/product_info.json","w") as f:
                        f.write(json.dumps(product,sort_keys=True,indent=2))

                return make_response(jsonify({"status":"success"}),200)
        else:
                return make_response(jsonify({"status":"part not found"}),400)

@app.route('/<product_id>/history', methods = ['GET'])
def get_history(product_id):
        nick = product_id_to_nick(product_id)
        with open(home+"/costs/products/"+nick+"/history.lst","r") as f:
                history = f.read();

        return make_response(jsonify(parse_history(history)),200)

@app.route('/<product_id>/links', methods = ['GET'])
def get_links(product_id):
        with open("links.json","r") as f:
                links = json.loads(f.read())

        return make_response(jsonify(links),200)

@app.route('/products', methods = ['GET'])
def get_products():
        products = getProductList()
        product_list = []
        print products
        for product in products:
                with open(home+"/costs/products/"+product+"/product_info.json","r") as f:
                        data = json.loads(f.read())
                product_list.append(data)
        return make_response(jsonify({"products":product_list}),200)

@app.route('/mirror/<path:path>')
def send_static(path):
    return send_from_directory('mirror', path)

def add_history(nick,type,info,funds):
        with open(home+"/costs/products/"+nick+"/history.lst","r") as f:
                history = f.read();

        newline = current_time()+" %|% "+type+" %|% "+info+" %|% "+funds+"\n"
        history = newline+history

        with open(home+"/costs/products/"+nick+"/history.lst","w") as f:
                f.write(history);

def product_id_to_nick(product_id):
        products = getProductList()
        for product in products:
                with open(home+"/costs/products/"+product+"/product_info.json","r") as f:
                        data = json.loads(f.read())
                if data["ID"] == product_id:
                        return product
        return False

def parse_history(history):
        output = []
        history = history.split("\n")
        for item in history:
                if len(item) > 1:
                        item = item.split(" %|% ")
                        date = item[0]
                        type = item[1]
                        info = item[2]
                        funds = item[3]
                        if funds[0] == "+":
                                color = "good"
                        elif funds[0] == "-":
                                color = "bad"
                        else:
                                color = "none"

                        output.append(
                                {
                                        "date":date,
                                        "type":type,
                                        "info":info,
                                        "funds":funds,
                                        "color":color,
                                }
                        )

        return output

def price_short(x):
        number = str(float(x))
        while len(number.split(".")[1]) < 2:
                number += "0"
        return number

def current_time():
        return strftime("%m-%d-%Y %I:%M:%S %p")

def getProductList():
        list = []
        products = os.listdir(home+"/costs/products")
        for product in products:
                if product[:1] != ".":
                        list.append(product)
        return list

#////////////////////////////////////////////////

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8080, debug = True, threaded=True)
