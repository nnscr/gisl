GISL
===================

GISL (GAIA Import Script Language) is a simple script language that can be used for multiple purposes.

It consists of a lexical scanner, a parser and an interpreter.

Syntax
-------------------

The syntax is pretty easy: there is just plain text and expressions.

Example:

    This is plain text @{"this is a string inside an expression"}

There are also methods that you can use like:

    @{"some input".replace("input", "data")}

Of course you can also chain methods like:

    @{"Pretty cool".replace("cool", "awesome").replace("Pretty", "Absolutely")}

If you do not want or need to pass any arguments to the function, you can even omit the parenthesis, like

    @{"dont yell at me!".ucase}

The next cool feature are identifiers. If you pass some identifiers to GISL, you can use them later on with the `[]` annotation.
This is incredible useful if you want to use GISL in a context sensitive environment like import scripts (what GISL was made for)

    @{[Some Field].replace("foo", "bar)}

The interpreter will now look up for an identifier with the name "Some Field", you can see an example of the usage [here](example/gisl.php).

But what if you really need a `@{` as plain text? You can just escape the @ by appending another @, like:

    Oh what a @@{"cool thing"}

    Outputs: Oh what a @{"cool thing"}

You need to escape strings or the identifier brackets? No problem, just use the C-escaping style with `\` like:

    @{"This is \"absolutely\" safe"}


Info
-------------------

If you want to contribute to GISL, please feel free to fork this project and create a pull request.
If you have any questions or problems please contact me or create an issue.

It would be really cool if someone could write more methods or write some tests, so feel free to do so.

The lexer and parser code is inspired by Twig.

You can use GISL wherever you want since it is released under the [MIT-License](LICENSE).
If you use this library in your project or use the code to build your own script language I would be pleased to hear from you.
