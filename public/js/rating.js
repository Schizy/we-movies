const form = document.getElementById('ratingForm');

function handleChange() {
    const inputRatings = document.querySelectorAll('input[name="rating"]');
    const submitBtn = document.querySelector('input[type="submit"]');

    inputRatings.forEach(input => {
        input.addEventListener('change', () => {
            if (input.checked === true) {
                submitBtn.disabled = false;
            }
        })
    })
}

handleChange();
