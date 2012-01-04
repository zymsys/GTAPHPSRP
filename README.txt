SOLID PHP & Code Smells - Part 1: Single Responsibility Principle
=================================================================

This is the source code used for the presentation I made to the GTA PHP Meetup Group 
on the Single Responsibility Principle (SRP).  The slides can be found on my blog at 
(http://blog.vicmetcalfe.com/2011/11/02/solid-php-code-smells-part-1-single-responsibility-principle/). 
If you have any comments, questions or criticisms I'd appreciate your comments there.

v0: Stinky Code without TDD 
--------------------------- 
The application I used in the demo was called *Times Table Challenge*, which is a 
simple times table quiz application.  In the presentation I showed v0.php, which is a 
version of the app I wrote without TDD or without applying any SOLID principals.

v1: Better with TDD but still Stinky
------------------------------------
We looked at the rest of the files which were written using TDD, and we talked about 
how the act of forcing ourselves to write testable code automatically pushes us 
towards SRP. See slide 9 for details.

v2: Tracking Right Answers
--------------------------
Then we introduced a change request to track how many questions were answered right on 
the first try, and we talked about how to approach the change request from a TDD 
perspective. We started by implementing the changes without any attempt to separate 
responsibilities so that we could see what code smells emerged.

v3: Improved Separation of Responsibilities
-------------------------------------------
Next we looked at the responsibilities of the TTC and TTCQuestion classes.  See slide 
15. Then we renamed the TTC class / file to MathDrillUI, and TTCQuestion to 
MultiplicationQuestion. We also added MathDrillStats with a bunch of getters and 
setters, and updated MathDrillUI to use our new MathDrillStats.

This is where we left it.  We moved the stats, questions and UI code into separate 
classes, but we left the grouped UI responsibilities together because of the degree of 
stink I found in the code.  If the grouped UI responsibilities became worse after 
other change requests I would factor those out in accordance with SRP.

Postscript
----------
I wrote this README two months after giving the presentation, and my memory is umm... 
Human.  I might have missed some important things.  I'll try to get presentation code 
online sooner in the future.  Let me know what I messed up on and I'll do my best to 
correct the issues.

