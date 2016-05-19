#!/usr/bin/env python

import binascii, os

def rand16():
    return binascii.b2a_hex(os.urandom(16))

print("\nTurtleduck HMAC key: %s\n" % rand16())
