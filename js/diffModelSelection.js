function CheckDiffModel(val){
 var element=document.getElementById('totalDiffModelNeuronsTxtField');
 if(val=='yes')
   element.disabled = true;
 else  
   element.disabled = false;
}
