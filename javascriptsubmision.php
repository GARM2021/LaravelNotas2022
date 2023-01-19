<script src="{{ asset('tmpl/swal/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript">
    //20220719  C24 20230110
    document.getElementById('btnPagarLinea').addEventListener('click', (e) => {
        e.preventDefault();
        console.log("este es ");
        console.log(e);

        FormPagoLinea.setAttribute('action', 'https://www.adquiramexico.com.mx/clb/endpoint/gGuadalupe');

       if (document.getElementById('ic_referencia').value == '05144045' || document.getElementById('ic_referencia').value == '04064002' || document.getElementById('ic_referencia').value == '20108019') {
              FormPagoLinea.setAttribute('action', 'https://prepro.adquiracloud.mx/clb/endpoint/gGuadalupe');             
             document.getElementById('it_servicio').value = "02";
            //confirm("es 01001012");
           // console.log("es 01001012 y no trece");
        }

        document.getElementById('FormPagoLinea').submit();
    });

</script>
This is JavaScript code that binds an event listener to the click event of an HTML element with the id "btnPagarLinea". When this element is clicked, the event listener is triggered, which first prevents the default action of the click event, and then performs several actions:

It sets the value of the "action" attribute of an HTML form with the id "FormPagoLinea" to a specific URL, "https://www.adquiramexico.com.mx/clb/endpoint/gGuadalupe".
It checks the value of an input element with the id "ic_referencia". If the value is "05144045", "04064002", or "20108019", then it sets the "action" attribute of the "FormPagoLinea" to a different URL "https://prepro.adquiracloud.mx/clb/endpoint/gGuadalupe", and sets the value of an input element with the id "it_servicio" to "02".
It submits the form by calling submit() method on the "FormPagoLinea" element.



Gabriel Antonio Del Real Márquez
cual es el padre del elemento "it_servicio"
The element "it_servicio" is an input element that is part of the "FormPagoLinea" form element, which is most likely its parent element.

It can be accessed via document.getElementById('FormPagoLinea') and it can also be accessed from the DOM tree via its parentNode property, so you can use document.getElementById('it_servicio').parentNode to get the parent element.

It should be noted that, Without the context of the complete code and the HTML, it's a little hard to give a proper answer about the parent element, but in general way all elements of a form will be inside a Form element with the same name, in this case "FormPagoLinea"