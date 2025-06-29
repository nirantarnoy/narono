<?php
?>


<div id=”PDFcontent“>
    <h3>First PDF</h3>



    <p>Content to be written in PDF can be placed in this DIV!</p>

</div>

<div id=”ignoreContent“>

    <p>Only for display and not in pdf</p>

</div>

<button id=”gpdf”>Generate PDF</button>

<?php
$JS=<<<JS
var pdfdoc = new jsPDF();
var specialElementHandlers = {
    '#ignoreContent': function (element, renderer) {
        return true;
    }
};

$(document).ready(function(){

    $("#gpdf").click(function(){

        pdfdoc.fromHTML($("#PDFcontent").html(), 10, 10, {
        'width': 110,
        'elementHandlers': specialElementHandlers
        });
        
        pdfdoc.save('First.pdf');
    
    });
});
JS;
$this->registerJs($JS,static::POS_END);
?>
