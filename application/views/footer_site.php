
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
      <strong>Copyright © 2020 PET - <a target="_blank" href="https://sites.google.com/view/petestrategias/inicio">
              <img src="../imagens/pet.png"  height="40" width="70"> </a>
          Template by Almsaeed Studio. All rights reserved.Desenv: Alex Lopes, Mateus Amaral e Thales Mota

  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?=base_url('/AdminLTE-2.4.3/bower_components/jquery/dist/jquery.min.js');?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=base_url('/AdminLTE-2.4.3/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
<!-- SlimScroll -->
<script src="<?=base_url('/AdminLTE-2.4.3/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
<!-- FastClick -->
<script src="<?=base_url('/AdminLTE-2.4.3/bower_components/fastclick/lib/fastclick.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('/AdminLTE-2.4.3/dist/js/adminlte.min.js');?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=base_url('/AdminLTE-2.4.3/dist/js/demo.js');?>"></script>

<!-- Select2 -->
<script src="<?=base_url('/AdminLTE-2.4.3/bower_components/select2/dist/js/select2.full.min.js');?>"></script>


<script>
//script do auto complete
//https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_autocomplete
function autocomplete(inp, arr, arr_id) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        //if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        if( arr[i].toUpperCase().includes(val.toUpperCase()) ){
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = arr[i].substr(0, val.length);
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' id='" + arr_id[i] + "' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              id = this.getElementsByTagName("input")[0].id;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();

              //redireciona para uma pagina
              //alert(id + ' - ' + inp.value);

              window.location.replace("/home/pesquisar_monitoria/"+ id );

          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });

}

var monitorias_id = <?php echo $busca_id?>;//[1,2];
var monitorias = <?php echo $busca_nome?>;//["opcao 1","opcao 2"];

 //$busca_id;
 //$busca_nome;

/*initiate the autocomplete function on the "navbar-search-input" element, and pass along the itens array as possible autocomplete values:*/
autocomplete(document.getElementById("navbar-search-input"), monitorias, monitorias_id);


</script>


</body>
</html>
