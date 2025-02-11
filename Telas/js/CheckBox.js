
    
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('input[name="options[]"]');
    
            selectAllCheckbox.addEventListener('change', function () {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
    
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(checkboxes).every((cb) => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            });
        });
