<?php
require_once("/home/johnzupon/zupons.net/htdocs/swam/swam.i.php");
$S = new Swam;

$h->extra = <<<EOF
  <script language="javascript" type="text/javascript">
if (self != top) {
    if (document.images)
        top.location.replace(window.location.href);
    else
        top.location.href = window.location.href;
}
  </script>
  <style type="text/css">
#weather-nonmembers {
  width: 13em;
  border: 1px solid black;
  padding: 1em 1em 0 1em;
  margin-top: 1em;
  margin-left: 1em;
  margin-right: auto;
}
  </style>
EOF;
list($top, $footer) = $S->getPageTopBottom($h);
echo <<<EOF
$top

<h1 align='center'>HTML for Bulliten Board Posts</h1>
<hr />

<p>You can make your Bulliten Board posts more attractive by adding some <b>HTML</b>
code to them. Here are a few examples that might help.</p>
<p>HTML stands for <i>Hyper Text Markup Language</i>. HTML uses <i>TAGS</i> to tell the
HTML rendering engine in your favorite browser how to display (or render) the text that
is sent to it.</p>
<p>
Tags are HTML symboles. Here a a few that will help you.
Note that most tags have a start and end symbole the start symbole looks like this:<br>
&lt;tag&gt;<br>
and the end symbole looks like this:<br>
&lt;/tag&gt;.</p>
<p>There are tags that have no end symbole, for example the line brake &lt;br&gt;,
you can also code this as &lt;br /&gt; which would be &quot;well formed&quot; xml and xhtml.</p>

<h2>Useful HTML Tags</h2>

<ul>
	<li>&lt;p&gt;this is the start of a paragraph and this is the end&lt;/p&gt;<br>
		It would look like this:<br><br>
		this is the start of a paragraph and this is the end<br><br>
		Paragraphs usually have an empty line above and below them (I&apos;ll say more about <b>usually</b>
		later)</li>
	<li>&lt;b&gt;<b>this makes your text bold</b>&lt;/b&gt;</li>
	<li>&lt;i&gt;<i>this makes your text italic</i>&lt;/i&gt;<br></li>
	<li>&lt;li&gt;this is a list. The section you are now reading is part of an indented list</li>
	<li>&lt;ul&gt;this causes a list to be indented. So this code:<br>
		<pre>
&lt;ul&gt;
    &lt;li&gt;Item one of list&lt;/li&gt;
    &lt;li&gt;Item two of list&lt;/li&gt;
&lt;/ul&gt;
		</pre>
		would look like this:
		<ul>
		<li>Item one of list</li>
		<li>Item two of list</li>
		</ul>
	</li>
	<li>this make a line break &lt;br /&gt;. This line break does not usually put a blank
		line above and bellow as the paragraph tag (&lt;p&gt;) does. Here is an example of three
		lines with &lt;br /&gt; after line one and two:<br><br>
		This is line one and it has a &lt;br /&gt; after it<br />
		This is line two. As you can see there is no blank line above this line<br />
		And this is line three. There is no blank line after line two.</li>
	<li>this makes a page seperation line across the page. It is also does not have an end tag.
		The tag is &lt;hr /&gt; and it looks like this:
			<hr />
		</li>
	<li>these tags are used for headings, they have the form &lt;h1&gt; and can have numbers other
		than one. The &lt;h1&gt; tag is the top level heading and is usually big and bold. The other
		tags usually are less big and bold in decending order. Here are a couple of examples:
		<h1>This is &lt;h1&gt;</h1>
		<h2>This is &lt;h2&gt;</h2>
		<h3>This is &lt;h3&gt;</h3>
		<h4>This is &lt;h4&gt;</h4>
		Different browsers are likely to display these differently, it is pretty much at the descression
		of the browser manufacturer how the heading look. All of these tags have both start and end tags.
	</li>
	<li>this tag is used to put hyper text links in your text. The tag looks like this:<br>
		&lt;a href=&quot;http://link-url&quot&gt;Some text goes here&lt;/a&gt;<br>
		The <i>href</i> element is called an <i>attribute</i> and most tags can accept <i>attributes</i>.
		Attributes change or augment the tag, more about this in a bit. This is a link to the Swam
		main page and it is coded like this: &lt;a href=&quot;http://www.swam.us&gt;Swam Home Page&lt;/a&gt;<br>
		and it looks like this on the page:<br>
		<a href="http://www.swam.us">Swam Home Page</a></li>
	<li>this tag lets you embed images in you text. &lt;img src=&quot;image-url&quot;&gt;. This tag is
		usually only a start tag (though you can put an end tag) so it should realy have a /&gt; at the end.
		Here is an example: &lt;img src=&quot;/images/back.gif&quot; /&gt;<br>
		Here is how it looks:<br>
		<img src="/images/back.gif" /><br>
		The link tag and the image tag are often put together like this:<br>
		&lt;a href=&quot;index-members.php&quot;&gt;&lt;img src=&quot;images/back.gif&quot alt=&quot;Back to Swam Home Page&quot; /&gt;&lt;/a&gt;<br>
		It looks like this:
		<a href="index-members.php"><img src="images/back.gif" alt="Back to Swam Home Page" /></a>
		If you click on this you will return to the <b>SWAM Home Page</b><br>
		Notice that in the above I didn&apos;t use the full &quot;http://www.swam.us/index-members.php&quot;
		or the &quot;/images/back.gif&quot;. You can use either a fully qualified URL (<i>Universal Resource Locator</i>)
		or a relative URL. Both &quot;/image/back.gif&quot; and &quot;image/back.gif&quot; are relative URL&apos;s.
		In the first case it is relative to the root of the <b>SWAM</b> web page and in the second it is
		relative to the current directory. The fully qualified URL looks like
		&quot;http://www.swam.us/images/back.gif&quot;. It is fully qualified because there is only one in the
		whole world.</li>
	<li>these tags can be used to create tables: &lt;<table&gt;, &lt;tr&gt;, &lt;th&gt, and &lt;td&gt;.
		Here is an example of making a table:
		<pre>
&lt;table border=&quot;1&quot;&gt;
  &lt;tr bgcolor=&quot;yellow&quot;&gt;
    &lt;th&gt;Item1&lt;/th&gt;&lt;th&gt;Item2&lt;/th&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td&gt;one&lt;/td&gt;&lt;td&gt;two&lt;/td&gt;
  &lt;/tr&gt;
  &lt;tr&gt;
    &lt;td&gt;three&lt;/td&gt;&lt;td&gt;four&lt;/td&gt;
  &lt;/tr&gt;
&lt;/table&gt;
		</pre>
		Here is what that looks like:
		<table border="1">
			<tr bgcolor="yellow">
				<th>Item1</th><th>Item2</th>
			</tr>
			<tr>
				<td>one</td><td>two</td>
			</tr>
			<tr>
				<td>three</td><td>four</td>
			</tr>
		</table>
	</li>
</ul>

<h2>Attributes of HTML Tags</h2>
<p>As I said most tags can have one or more <i>attributes</i>. Most <i>attributes</i> take an
argument and look like: <i>attribute=&quot;value&quot</i>. Here are some of the most common
and which fit with most tags.</p>

<ul>
	<li><i>align=</i>: This applies to most tags, for example, to make a heading centered on the page
		you could write: &lt;h1 align=&quot;center&quot;&gt;Center Me&lt;/h1&gt;. This looks like:
		<h1 align="center">Center Me</h1>
		The other possible values are: <i>right, left, justify</i>. To make a paragraph justify you could
		write &lt;p align=&quot;justify&quot;>Some text....&lt;/p&gt;. Here is how that might look:
		<p align="justify">This is a long section of text that will justify at the right side
		and produce a straight left margin instead of the raged margins you see in the rest of
		this page. If you make your browser smaller you will see this better as you will get more
		lines. --- This is a long section of text that will justify at the right side
		and produce a straight left margin instead of the raged margins you see in the rest of
		this page. If you make your browser smaller you will see this better as you will get more
		lines</p>
		</li>
	<li><i>width=</i> and <i>height=</i>: These control the width and height of some tag elements.
		For example, the image tag uses these to control the size of the image. If we use the same
		&quot;back.gif&quot; image but change the width and height like this:
		&lt;img src=&quot;images/back.gif&quot; width=&quot;200&quot; height=&quot;200&quot; /&gt;<br>
		Then we get this: <img src="images/back.gif" width="200" height="200" /> and the image is a lot
		bigger. Note that making an image bigger then the original causes aliasing which is the
		stair-step rather than smooth edges on the arrow. If you have a big image you can make is smaller
		without any ill effects, but making a big image smaller like this does not improve download
		performance because the full image still must be transfered from the <i>server</i> to your
		browser. It is the browser that scales the image in this case.</li>
	<li><i>bgcolor=</i>: Change the back ground from the default (usually white) to some other color.
		We did this above in the table example.
		</li>
	<li><i>style=</i>: This is very powerful as it lets you use the full <b>CSS</b> (Casscading Stye Sheets)
		specification on almost any tag. Here is a quick example:
		&lt;p style=&quot;color: white; background-color: red&quot;&gt;This has a red background and white text&lt;/p&gt;.
		It looks like this:
		<p style="color:white; background-color: red">This has a red background and white text</p>
		</li>

</ul>

<hr>

If you would like more information on HTML tags and much more take a look
at <a href="www.w3c.org">www.w3c.org</a>
that is the site of the World Wide Web Consortium who work at setting standards for the web.
They have information on all the cool (geekie) new stuff,
as well as the plain old HTML specifications. 
There are also some pretty straight forward books out there on HTML coding.
Some of the &quot;For Idiots&quot; and &quot;For Dummies&quot; books are actually quite good and
down to earth even if you don&apos;t speak <b><i>GEEK</i></b>
$footer
EOF
?>
