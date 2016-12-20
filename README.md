# COSTS
Connor's Open Stock Tracking System!

----------
# Disclaimer

- This software was written with none of the care I normally take to optimize code.
- I'm not the hippest Python developer.
- I wrote this for personal use only, but I'm putting it out there to see if anyone finds it useful.

----------
# About

Connor's Open Stock Tracking System ("COSTS" for short) is a solution I wrote as a hardware maker with small-to-medium scale production to keep track of my inventory, package locations, and budget. It has a few built in calculations which make solving the cost of producing your hardware product easier! For example:

Here is our bill of materials with pricing for a decent-sized order:

| **PART NAME**    | **NEEDED PER** | **ORDER COST** | **ORDER QUANTITY** | **SHIPPING COST** |
|------------------|----------------|----------------|--------------------|-------------------|
| 1000uF Capacitor | 1              | $12.63         | 100                | $3.40             |
| WS2812B          | 9              | $59.76         | 1000               | $20.14            |
| PCB              | 1              | $69.90         | 200                | $28.11            |
| ESP-12E          | 1              | $105.80        | 50                 | $0.00             |
