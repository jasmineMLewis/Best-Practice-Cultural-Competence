function processPurposeOfResearch(){
		document.getElementById("purposeOfResearch").style.display = "none";
		document.getElementById("questsDimension").style.display = "block";
		document.getElementById("phaseName").innerHTML = "Quests Dimension";
                document.getElementById("progressBar").value = 100;
}

function processBackToPurposeOfResearch() {
    document.getElementById("questsDimension").style.display = "none";
    document.getElementById("purposeOfResearch").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Purpose of Research";
    document.getElementById("progressBar").value = 100;
}

function submitForm(){
	document.getElementById("multiphase").method = "post";
	document.getElementById("multiphase").action = "";
	document.getElementById("multiphase").submit();
}