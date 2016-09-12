<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <body>
        <?php
        if (preg_match('/192.168.2./i',$_SERVER['REMOTE_ADDR']) || preg_match('/192.168.4./i',$_SERVER['REMOTE_ADDR']))
        {
        ?>
        
        <table>
            <tr>
                <td><a href="http://jms.bluehaven.local/phone/officedirlookup.php">Office Directory</a></td>
            </tr>
            <tr>
                <td><a href="http://jms.bluehaven.local/phone/vendordirlookup.php">Vendor Directory</a></td>
            </tr>
            <tr>
                <td><a href="http://jms.bluehaven.local/phone/zipcodelookup.php?a=start">IVR Zip Code Lookup</a></td>
            </tr> 
        </table>
        
        <?php
        }
        else
        {
        ?>
        
        <table>
            <tr>
                <td>Not Authorized</td>
            </tr>
        </table>
          
        <?php  
        }
        ?>
    </body>
</html>