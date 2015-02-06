# samverify
[![Build Status](https://travis-ci.org/soren121/samverify.svg?branch=master)](https://travis-ci.org/soren121/samverify)

Simple account verification plugin for Spigot 1.8+.

The idea:

1. A user attempts to register on the server's website to gain access to additional features.
2. The website prompts them to login to the server and type `/register USERNAME`.
3. The MC server responds with a one-time-use authentication code, hashed with their UUID, which confirms that they are the player with that UUID on the server.
4. The user gives the website the authentication code.
5. The webserver checks the Mojang Account API and confirms that the username and UUID match.
6. If all is well, they're given an account on the website.
