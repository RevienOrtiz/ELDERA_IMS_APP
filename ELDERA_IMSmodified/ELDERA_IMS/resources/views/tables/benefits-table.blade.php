<!-- Benefits Applicants Table -->
<div class="table-container" id="existing-senior-table" style="display: none;">
    <table class="records-table">
        <thead>
            <tr>
                <th>NO.</th>
                <th>OSCA ID NO.</th>
                <th>FULL NAME</th>
                <th>AGE</th>
                <th>Gender</th>
                <th>Barangay</th>
                <th>MILESTONE AGE</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($benefitsApplications ?? [] as $index => $application)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $application->senior ? $application->senior->osca_id : 'N/A' }}</td>
                <td>{{ $application->senior ? $application->senior->first_name . ' ' . $application->senior->last_name : 'N/A' }}</td>
                <td>{{ $application->senior ? \Carbon\Carbon::parse($application->senior->date_of_birth)->age : 'N/A' }}</td>
                <td>{{ $application->senior ? $application->senior->sex : 'N/A' }}</td>
                <td>{{ $application->senior ? ucfirst($application->senior->barangay) : 'N/A' }}</td>
                <td>{{ $application->benefitsApplication && $application->benefitsApplication->milestone_age ? $application->benefitsApplication->milestone_age : 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ $application->status }}" title="Status: {{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('seniors.benefits.view', $application->id) }}" class="btn btn-info" title="View">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('seniors.benefits.edit', $application->id) }}" class="btn btn-warning" title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger" title="Delete" onclick="showDeleteModal('{{ $application->id }}', '{{ $application->senior ? $application->senior->first_name . ' ' . $application->senior->last_name : 'N/A' }}', 'benefits')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No benefits applications found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
