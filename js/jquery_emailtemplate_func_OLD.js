$(document).ready(function()
{

   $('.tdraggable').draggable({revert:true});
   $('.tdroppable').droppable();
   
   function CopytoClipBoardNew(e,h)
   {
	  document.getElementById(h).innerText = e;
	  Copied = document.getElementById(h).createTextRange();
	  Copied.execCommand("Copy");
		
	  alert(e + ' copied to Clipboard')
   }
   
   
});