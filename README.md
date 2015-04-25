Small symfony 2.6 project
===============

Task description
-----------------

 Let's build a small application which is capable of rendering png widgets (images) for users. The users should be stored in the database and have at least the following information:

    some hash that can be used as a public identifier
    some information about the status (could be locked or inactive for example)

The app should have one public endpoint that can return image data and respects the semantics of http status codes. It should only return image data for active users (uses the state information mentioned above). The widget itself should have 4 dynamic attributes:

    width (number in px between 100 and 500)
    height (number in px between 100 and 500)
    background-color (hex value, e.g. 000000 means black)
    text-color (hex value)

Those attributes and the public identifier of the user should be included somehow in the route/path for the endpoint and thus used and validated by the app to generate a correct widget. The content of the widget itself is only one number (percentage of average rating of this user) and can have a random value. Attached you find one example of a widget with 100*100 px and black background + white text.
Some further requirements:

    Symfony 2.6
    Mysql database
    Tests ;)


