To send the newsletter, run this command:

`php bin/console mailing:send`

If you are in **production** environment, the mailing WILL get sent to the end users.

If you want to test on live data, you can run this command:

`php bin/console mailing:send -t=1`