<?php

//function generate_ret ()
//{
      echo "<html>\n";
      echo "<head>\n";
      echo "	<META NAME=\"ROBOTS\" CONTENT=\"NOINDEX, NOFOLLOW\">\n";
      echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"./bh.css\" />\n";
      echo "   <title>Blue Haven Pools and Spas</title>\n";
      echo "</head>\n";
      echo "<body>\n";
      echo "<form action=\"return_XML.php\" method=\"POST\" target=\"_top\">\n";
      //echo "<input type=\"hidden\" name=\"g\" value=\"x\"></td>\n";
      echo "<table align=\"center\" border=\"0\">\n";
      echo "<tr><td>\n";
      echo "   <table>\n";
      echo "   <tr>\n";
      echo "      <td valign=\"bottom\">\n";
      echo "         <table>\n";
      echo "         <tr>\n";
      echo "            <td align=\"right\">Code:</td>\n";
      echo "            <td><input type=\"text\" name=\"action\" size=\"64\" maxlength=\"64\" value=\"p\"></td>\n";
      echo "         </tr>\n";
      echo "         <tr>\n";
      echo "            <td align=\"right\">Office:</td>\n";
      echo "            <td><input type=\"text\" name=\"oid\" size=\"64\" maxlength=\"64\" value=\"901\"></td>\n";
      echo "         </tr>\n";
      echo "         <tr>\n";
      echo "            <td align=\"right\">Security:</td>\n";
      echo "            <td><input type=\"text\" name=\"uid\" size=\"64\" maxlength=\"64\" value=\"muser\"></td>\n";
      echo "         </tr>\n";
      echo "         <tr>\n";
      echo "            <td colspan=\"2\" align=\"right\"><button type=\"submit\">Request</button></td>\n";
      echo "         </tr>\n";
      echo "         </table>\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   </table>\n";
      echo "</td></tr></table>\n";
      echo "</form>\n";
      //echo $HTTP_SERVER_VARS['PHP_SELF'];
      echo "</body>\n";
      echo "</html>";
//}

?>