<tbody id="employee-table-body">
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td><a href="{{ route('employees/byDepartment', $user->department) }}">{{ $user->department }}</a></td>
            <td>{{ $user->role_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number }}</td>
            <td>{{ $user->status }}</td>
            <td>
                <button class="btn btn-danger delete-employee" data-id="{{ $user->id }}">
                    <i class="fa fa-trash"></i>
                </button>
                <a href="{{ route('employees.edit', $user->id) }}" class="btn btn-warning">
                    <i class="fa fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
</tbody> 