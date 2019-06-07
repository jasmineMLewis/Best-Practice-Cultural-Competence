function processBackground() {
    document.getElementById("background").style.display = "none";
    document.getElementById("methods").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Methods";
    document.getElementById("progressBar").value = 16.666;
}

function processBackToBackground() {
    document.getElementById("methods").style.display = "none";
    document.getElementById("background").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Background";
    document.getElementById("progressBar").value = 16.666;
}


function processMethods() {
    document.getElementById("methods").style.display = "none";
    document.getElementById("participants").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Participants";
    document.getElementById("progressBar").value = 33.3333;
}

function processBackToMethods(){
      document.getElementById("participants").style.display = "none";
    document.getElementById("methods").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Method";
    document.getElementById("progressBar").value = 33.3333;
}

function processParticipants() {
    document.getElementById("participants").style.display = "none";
    document.getElementById("interventions").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Interventions";
    document.getElementById("progressBar").value = 50.00000;
}

function processBackToParticipants(){
     document.getElementById("interventions").style.display = "none";
    document.getElementById("participants").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Participants";
    document.getElementById("progressBar").value = 50.00000;
}

function processInterventions() {
    document.getElementById("interventions").style.display = "none";
    document.getElementById("assessment").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Assessment";
    document.getElementById("progressBar").value = 66.66666;
}

function processBackToInterventions(){
    document.getElementById("assessment").style.display = "none";
    document.getElementById("interventions").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Interventions";
    document.getElementById("progressBar").value = 66.66666;
}

function processAssessment() {
    document.getElementById("assessment").style.display = "none";
    document.getElementById("result").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Results";
    document.getElementById("progressBar").value = 83.33333;
}

function processBackToAssessment(){
    document.getElementById("result").style.display = "none";
    document.getElementById("assessment").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Assessment";
    document.getElementById("progressBar").value = 83.33333;
}

function processResult() {
    document.getElementById("result").style.display = "none";
    document.getElementById("miscellaneous").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Miscellaneous";
    document.getElementById("progressBar").value = 100;
}

function processBackToResults(){
    document.getElementById("miscellaneous").style.display = "none";
    document.getElementById("result").style.display = "block";
    document.getElementById("phaseName").innerHTML = "Results";
    document.getElementById("progressBar").value = 100;
}

function submitForm() {
    document.getElementById("multiphase").method = "post";
    document.getElementById("multiphase").action = "";
    document.getElementById("multiphase").submit();
}