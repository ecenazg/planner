<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
    <style>
        .department-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Departments</h1>

    @foreach ($departments as $department)
    <button class="department-button" onclick="showDoctors('{{ $department->id }}')">{{ $department->name }}</button>

    @endforeach

    <div id="doctors-container"></div>

    <script>
        function showDoctors($id) {
            // Send an AJAX request to fetch the doctors of the department
            fetch(`/department/${id}/doctors`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('doctors-container').innerHTML = data;
                });
        }
    </script>
</body>
</html>
