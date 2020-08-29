const customers = document.getElementById('customers');


if (customers) {
    customers.addEventListener('click', (e) => {
        if (e.target.className === 'btn btn-danger delete-customer') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/customer/delete/${id}`,
                    {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }endCustomerAppointment
        }
        else if (e.target.className === 'btn btn-success update-customerAppointment') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/customer/updateAppointment/${id}`,
                    {
                        method: 'UPDATE'
                    }).then(res => window.location.reload());
            }
        }
        else if (e.target.className === 'btn btn-success update-endCustomerAppointment') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/customer/endAppointment/${id}`,
                    {
                        method: 'END'
                    }).then(res => window.location.reload());
            }
        }

    });
}