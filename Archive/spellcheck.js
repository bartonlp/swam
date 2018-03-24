oShell= new
ActiveXObject("WScript.Shell");
oShell.SendKeys("^c"); // copy
oWord = new ActiveXObject("Word.Application");
oWord.Visible = true;
oWord.Documents.Add();
oWord.Selection.Paste();
oWord.ActiveDocument.CheckSpelling();
oWord.Selection.WholeStory();
oWord.Selection.Copy();
oWord.ActiveDocument.Close(0);
oWord.Quit();
var nRet = oShell.popup("Apply changes?\nClick OK to replace all selected. text.", 0, "Spell Check Complete", 33);
if(nRet == 1) {
	oShell.SendKeys("^v");
}

				 