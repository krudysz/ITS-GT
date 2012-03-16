<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link rel="stylesheet" href="css/docs.css">
        <title>DOCS</title>
    </head>
    <body>
        <h3 class="DATA">SYSTEM: Kerberos (OIT) passwords</h3>

        <ol>
            <li>copy the configuration file <a href="krb5.conf" target="_blank">krb5.conf</a> to: <code>/etc/krb5.conf</code></li>
            <li><code>/usr/bin/system-config-authentication</code> and check the "Enable
                Kerberos" under the Authentication tab <br>
                ssh -X root@machinename /usr/bin/system-config-authentication <br>
                Works from a linux command line, or from a Mac command terminal if
                Xwindows is running.
            </li>
            <li>Use the <code>adduser</code> command to create the user accounts MATCHING the
                user's OIT login ID. Do not create a password for the user.</li>
            <li><code>adduser gte269x</code></li>
        </ol>
<p>            
</body>
</html>