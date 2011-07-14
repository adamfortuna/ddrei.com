{               CacheRight SAMPLE RULES FILE (rules.cr)



Welcome to CacheRight, the IIS Web server tool that makes it easy to
implement effective cache control policies for your entire Web site.

This is an example of a rules.cr file, where you write the rules that
CacheRight then uses to implement your cache control policies.  The
rules in this file control the HTTP headers (especially "Expires" and
"Cache-Control") that tell browser and proxy caches whether and for how
long a given file should be cached.  This file must be located in the 
root directory of your Web site.  Everything written in this rules file
between curly braces is a comment and will be ignored by CacheRight.


USING THE EXAMPLE RULES

The CacheRight rules given here are intended as examples. A good way to
get started with CacheRight is to uncomment some example rules and
examine the effect of the changes in the HTTP response headers (you 
may need to create some test files or change some paths in the rules to
make this work).  To uncomment an example rule, remove the curly braces
that surround it.


VALIDATING THE RULES FILE

Whenever you make any changes to the rules file, you should validate it
using the syntax checker provided with CacheRight (cr_syntax.exe is
installed with CacheRight by default; a shortcut to cr_syntax.exe is located
under Start, Programs, Port80, CacheRight, CacheRight Syntax Checker).
If you are working directly on the IIS server, click the Validate
button on the CacheRight user interface to launch the Syntax Checker.
If you are editing the rules file on another computer, you should keep
a copy of cr_syntax.exe on that computer.


RELOADING THE RULES FILE

For rule changes to take effect, you must tell CacheRight to reload the
rules file.  If you are editing rules.cr directly on the IIS server, do
this by clicking the Apply button in the CacheRight user interface.  If
you are editing the rules file remotely and uploading it to your site's
root directory, you can reload it by requesting any file in the site
using the cr_reset query parameter:  

	 http://hostname/?cr_reset



CacheRight EXAMPLE RULES

Three types of CacheRight rules are available:

	 >1. ExpiresDefault
	 >2. ExpiresByPath
	 >3. ExpiresByType  }  



{------- >1. ExpiresDefault ------------------------------------------}


{  The ExpiresDefault rule will be enforced when no other rule in the
rules file applies to a particular request.  You may want to set this
to expire immediately, so as not to inadvertently cause something to be
cached that changes frequently, or you may want to set an expiration
time that reflects the typical rate at which content is changed on your
site, say every 2 weeks.  Uncomment only one of these at a time.  }


 ExpiresDefault : immediately public
{ExpiresDefault : 2 weeks after modification public}



{------- >2. ExpiresByPath -------------------------------------------}


{  If you want to set an expiration rule to cover a certain region of
your site, or certain files within that region, use an ExpiresByPath
rule.  This example sets the expiration for all the files in a
directory called navimgs that is a sub-directory of the images 
directory.  The rule states that the cached copies of these files won't
expire until 6 months after they are accessed by the user.  You might
use such a rule to make sure that your navigation images, which
probably don't change very often, are cached properly.  }


{ExpiresByPath /images/navimgs/* : 6 months after access public}



{  Here is a very similar rule that might, however, have a very
different effect in particular cases.  The only difference in the rule
is that the expiration interval no longer begins with the date on which
the file was accessed by the end user; instead, the cached copy will
expire 6 months after the last time it was modified.  This obviously
could translate into a much shorter expiration time for a particular
file accessed at a particular time.  It might even mean immediate
expiration.  }


{ExpiresByPath /images/navimgs/* : 6 months after modification public}



{  Here is a variation on the previous example that singles out the
navigation images not by subdirectory but by using a naming convention
that distinguishes navigation images from other files in the images
directory.  In this case, the convention would be that all navigation
images are jpegs ending in "nav."  }


{ExpiresByPath /images/*nav.jpg : 6 months after modification public}



{  For objects that are practically unchanging, you can set a maximum
expiration time of up to one year, as in this example below.  You could go
longer than a year by using a number and the "years" keyword, but the
HTTP specification discourages setting expiration intervals in excess
of one year.  Note that this rule also adds a special directive to
prevent proxy caches from transforming the object in order to compress
it.  You can add this "no-transform" directive to any rule you wish.  }


{ExpiresByPath /images/logos/* : never public no-transform}



{  Here is another example that introduces a new directive.  So far,
all the rules have used the "public" directive, meaning that the
affected objects are cacheable by public or shared proxy caches.  This
example substitutes the "private" directive, which makes the affected
objects cacheable only by non-shared caches, such as browser caches.  }


{ExpiresByPath /images/userimgs/* : 1 month after access private}



{  Since more specific rules always override more general ones, it
would be possible to have a general rule for everything in or below the
images directory, while still using any or all of the rules in the
previous examples.  Note that this rule also uses the date of
modification, rather than the date of access, as the starting date for
the expiration interval.  }


{ExpiresByPath /images/* : 1 month after modification public}



{-------- >3. ExpiresByType ------------------------------------------}


{  You could accomplish much the same thing, while also taking care of
any images that happen to live outside the /images branch, by using a
different kind of rule, the ExpiresByType rule.  This rule picks out
objects by their MIME types.  It is considered more general than any
ExpiresByPath rule, so the ExpiresByType rule will only apply if there
are no ExpiresByPath rules that apply to the same object.
ExpiresByType is a handy way to set the expiration for a given class of
objects on a site-wide basis -- in this case, all jpeg images.  }


{ExpiresByType image/jpeg : 1 month after modification public}



{-------- Additional Features Available in CacheRight Rules ----------}


{  Both kinds of rule -- ExpiresByPath and ExpiresByType -- can have
more than one clause that picks out files.  Simply separate the clauses
with commas, as in these examples.  }


{ExpiresByPath /navimgs/*, /logos/* : never public}
{ExpiresByType image/jpeg, image/gif : never public}



{  Expiration intervals can use any combination of time units including
years, months, weeks, days, hours and minutes.  For example, the
following rule uses both months and days.  }


{ExpiresByType text/html : 1 month 15 days after modification public}



{  In addition to relative expiration intervals, you can set absolute
expiration date/times, using the GMT format, as in the following
example.  Be sure to follow the GMT format exactly; otherwise, the 
rules.cr file will not validate correctly, and you'll get a syntax
error from cr_syntax.exe.  }


{ExpiresByType text/html : Thu, 01 May 2003 12:00:01 GMT private}




{  ADDITIONAL SUPPORT: For an evaluation guide with HTTP header
analysis tools, directions on cache control policies, and other tips,
please visit http://www.cacheright.com.  }