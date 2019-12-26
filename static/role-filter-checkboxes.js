(function() {
    
    const form = document.getElementById('filter_form');
    const checkboxes = document.querySelectorAll('#filter_form input[type=checkbox]');
    checkboxes.forEach(function (e) {
        e.addEventListener('change', function() {
            form.submit();
        });
    });
        
    
})();

