<!DOCTYPE html>
<html>
<head>
    <title>Doctor Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cbbcf6;
            margin: 0;
            padding: 20px;
        }
        
        h1 {
            color: #512b58;
            text-align: center;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #35013f;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        table p {
            margin: 0;
            padding: 10px;
        }
        
        form {
            margin-top: 20px;
        }
        
        label {
            display: inline-block;
            width: 100px;
            margin-right: 10px;
        }
        
        input[type="text"],
        input[type="email"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }
        
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        
        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h1>Doctor Management</h1>

    <!-- Display doctors -->
    @if(count($doctors) > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                    <tr>
                        <td>{{ $doctor->id }}</td>
                        <td>
                            <span>{{ $doctor->name }}</span>
                            <input type="text" class="edit-field" value="{{ $doctor->name }}" style="display: none;">
                        </td>
                        <td>
                            <span>{{ $doctor->email }}</span>
                            <input type="text" class="edit-field" value="{{ $doctor->email }}" style="display: none;">
                        </td>
                        <td>
                            <span>{{ $doctor->specialization }}</span>
                            <input type="text" class="edit-field" value="{{ $doctor->specialization }}" style="display: none;">
                        </td>
                        <td>
                            <button class="edit-button">Edit</button>
                            <button class="save-button" style="display: none;">Save</button>
                            <form class="delete-form" action="{{ route('doctors.destroy', $doctor->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No doctors found.</p>
    @endif

    <!-- Create doctor form -->
    <h2>Create Doctor</h2>
    <form action="{{ route('doctors.create') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="specialization">Specialization:</label>
        <input type="text" name="specialization" required><br>

        <button type="submit">Create</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
  const editButtons = document.querySelectorAll('.edit-button');
  const saveButtons = document.querySelectorAll('.save-button');
  const editFields = document.querySelectorAll('.edit-field');
  const spans = document.querySelectorAll('td span');

  editButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      spans[index].style.display = 'none';
      editFields[index * 3].style.display = 'inline-block';
      editFields[index * 3 + 1].style.display = 'inline-block';
      editFields[index * 3 + 2].style.display = 'inline-block';
      editButtons[index].style.display = 'none';
      saveButtons[index].style.display = 'inline-block';
    });
  });

  saveButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
      const doctorId = button.parentElement.parentElement.querySelector('td:first-child').textContent;
      const formData = new FormData();
      const name = editFields[index * 3].value;
      const email = editFields[index * 3 + 1].value;
      const specialization = editFields[index * 3 + 2].value;

      formData.append('name', name);
      formData.append('email', email);
      formData.append('specialization', specialization);

      fetch(`/doctors/${doctorId}`, {
        method: 'PUT',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            throw new Error('Failed to save changes.');
          }
        })
        .then(data => {
          // Assuming you have a <tbody> element with the id "doctor-table-body"
          const doctorTableRow = document.getElementById(`doctor-row-${doctorId}`);
          doctorTableRow.innerHTML = `
            <td>${doctorId}</td>
            <td><span>${data.name}</span></td>
            <td><span>${data.email}</span></td>
            <td><span>${data.specialization}</span></td>
            <td>
              <button class="edit-button">Edit</button>
              <button class="save-button" style="display: none;">Save</button>
            </td>
          `;

          // Rebind the event listeners to the newly created edit and save buttons
          const newEditButton = doctorTableRow.querySelector('.edit-button');
          const newSaveButton = doctorTableRow.querySelector('.save-button');
          const newEditFields = doctorTableRow.querySelectorAll('.edit-field');
          newEditButton.addEventListener('click', handleEdit);
          newSaveButton.addEventListener('click', handleSave);
          newEditFields.forEach(field => {
            field.addEventListener('input', handleInputChange);
          });
        })
        .catch(error => {
          console.error('An error occurred while saving changes:', error);
        });
    });
  });
});

    </script>
</body>
</html>
