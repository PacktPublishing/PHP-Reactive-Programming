#PHP Reactive Programming
This is the code repository for [PHP Reactive Programming](https://www.packtpub.com/web-development/php-reactive-programming?utm_source=github&utm_medium=repository&utm_campaign=9781786462879), published by [Packt](https://www.packtpub.com/?utm_source=github). It contains all the supporting project files necessary to work through the book from start to finish.
## About the Book
Reactive Programming helps us write code that is concise, clear, and readable. Combining the power of reactive programming and PHP, one of the most widely used languages, will enable you to create web applications more pragmatically.
##Instructions and Navigation
All of the code is organized into folders. Each folder starts with a number followed by the application name. For example, Chapter02.



The code will look like the following:
```
Rx\Observable::just('{"value":42}')
    ->lift(function() {
        return new JSONDecodeOperator();
    })
    ->subscribe(new DebugSubject());
```

The main prerequisites for most of this book are a PHP 5.6+ interpreter and any text editor. We’ll use the Composer (https://getcomposer.org/) tool to install all external dependencies in our examples. Some basic knowledge of Composer and PHPUnit is helpful but not absolutely necessary.
In later chapters, we’ll also use the pthreads PHP extension, which requires PHP 7 or above and the Gearman job server; both should be available for all platforms. Also, some basic knowledge of the Unix environment (sockets, processes, signals, and so on) is helpful.

##Related Products
* [Reactive Programming ](https://www.packtpub.com/application-development/reactive-programming?utm_source=github&utm_medium=repository&utm_campaign=9781785885853)

* [Go Reactive Programming](https://www.packtpub.com/application-development/go-reactive-programming?utm_source=github&utm_medium=repository&utm_campaign=9781787129863)

* [Python Reactive Programming](https://www.packtpub.com/application-development/python-reactive-programming?utm_source=github&utm_medium=repository&utm_campaign=9781786463449)

###Suggestions and Feedback
[Click here](https://docs.google.com/forms/d/e/1FAIpQLSe5qwunkGf6PUvzPirPDtuy1Du5Rlzew23UBp2S-P3wB-GcwQ/viewform) if you have any feedback or suggestions.
