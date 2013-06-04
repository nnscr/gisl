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

The next cool feature are identifiers. If you pass some identifiers to GISL, you can use them later on with the `[]` annotation.
This is incredible useful if you want to use GISL in a context sensitive environment like import scripts (what GISL was made for)

    @{[Some Field].replace("foo", "bar)}

The interpreter will now look up for an identifier with the name "Some Field", you can see an example of the usage [here](gisl.php).

But what if you really need a `@{` as plain text? You can just escape the @ by appending another @, like:

    Oh what a @@{"cool thing"}

    Outputs: Oh what a @{"cool thing"}

You need to escape strings or the identifier brackets? No problem, just use the C-escaping style with `\` like:

    @{"This is \"absolutely\" safe"}
