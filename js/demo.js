document.querySelectorAll('.delete-btn').forEach(button => {
	button.onclick = function() {
		let id = jQuery(this).attr("data-id");
		let isDeleteConfirm = window.confirm("Are you sure?");
		if(isDeleteConfirm) {
			jQuery.ajax({
				url: frontend_ajax_object.ajaxurl,
				type: 'post',
				data: {
					'action':'delete_ingredient',
					'id': id
				},
				success: function( response ) {
					window.location.reload();
				},
				error: function( response ) {
					console.log(response);
				}
			});
		}
	}
})


let calories = 0;
let protiens = 0;
document.querySelectorAll('.trs').forEach(tr => {
	tr.onclick = function() {
		var elements = tr.children;
		let ingredientCalories = elements.item(3).innerHTML;
		let ingredientProtiens = parseFloat(elements.item(2).innerHTML);
		ingredientCalories = parseFloat(ingredientCalories);
		if(tr.style.backgroundColor == 'lightgreen'){
			tr.style.backgroundColor = 'transparent';		
			calories -= ingredientCalories;
			protiens -= ingredientProtiens;
		}
		else {
			tr.style.backgroundColor = 'lightgreen';	
			calories += ingredientCalories;
			protiens += ingredientProtiens;
		}
		let checkCalories = Math.floor(calories * 10) / 10;
		if(checkCalories < 0) {
			checkCalories = 0;
			calories = 0;
		}
		let checkProtiens = Math.floor(protiens * 10) / 10;
		if(checkProtiens < 0) {
			checkProtiens = 0;
			protiens = 0;
		}
		document.getElementById('calories-sum').innerHTML = checkCalories;
		document.getElementById('protiens-sum').innerHTML = checkProtiens;
	}
})

jQuery('#clear-btn').on('click', function() {
	document.querySelectorAll('.trs').forEach(tr => tr.style.backgroundColor = 'transparent');	
	calories = 0;
	protiens = 0;
	document.getElementById('calories-sum').innerHTML = 0;
	document.getElementById('protiens-sum').innerHTML = 0;
})

document.querySelectorAll('.edit-btn').forEach(btn => {
	btn.onclick = function() {
		let id = btn.getAttribute("data-id");
		let ingredient = btn.getAttribute("data-ingredient");
		let protien = btn.getAttribute("data-protien");
		let calories = btn.getAttribute("data-calories");
		jQuery('#edit-id').val(id);
		jQuery('#edit-ingredient').val(ingredient);
		jQuery('#edit-protien').val(protien);
		jQuery('#edit-calories').val(calories);
	}
})

jQuery('#update-btn').on('click', function($) {
	let id = jQuery('#edit-id').val();
	let ingredient = jQuery('#edit-ingredient').val();
	let protien = jQuery('#edit-protien').val();
	let calories = jQuery('#edit-calories').val();
	jQuery.ajax({
		url: frontend_ajax_object.ajaxurl,
		type: 'post',
		data: {
			'action':'update_ingredient',
			'id': id,
			'ingredient': ingredient,
			'protien': protien,
			'calories': calories
		},
		success: function( response ) {
			console.log(response);
			window.location.reload();
		},
		error: function( response ) {
			console.log(response);
		}
	});
})

jQuery('#insert-form').on('submit', function(e) {
	e.preventDefault();
	let formData = new FormData(this);
	formData.append('action', 'insert_ingredient');
	$.ajax({
         url: frontend_ajax_object.ajaxurl,
   type: "POST",
   data:  formData,
   contentType: false,
         cache: false,
   processData:false,
   success: function(data)
      {
		  window.location.reload();
      },
     error: function(e) 
      {
   			console.log(e);
      }          
    });
})