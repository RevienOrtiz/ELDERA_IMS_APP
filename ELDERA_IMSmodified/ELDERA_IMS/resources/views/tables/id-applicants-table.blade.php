<!-- Senior ID Applicants Table -->
<div class="table-container" id="id-applicants-table" style="display: block !important;">
    <table class="seniors-table">
        <thead>
            <tr>
                <th>OSCA ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Barangay</th>
                <th>Date Applied</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($idApplications as $index => $application)
            <tr>
                <td>{{ $application->senior ? $application->senior->osca_id : 'N/A' }}</td>
                <td>
                    @if($application->senior)
                        {{ $application->senior->full_name }}
                    @else
                        <span class="text-muted">No Senior Linked</span>
                    @endif
                </td>
                <td>
                    @if($application->senior && $application->senior->date_of_birth)
                        {{ \Carbon\Carbon::parse($application->senior->date_of_birth)->age }} years
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $application->senior ? ucfirst($application->senior->sex) : 'N/A' }}</td>
                <td>{{ $application->senior ? ucfirst($application->senior->barangay) : 'N/A' }}</td>
                <td>{{ $application->submitted_at ? $application->submitted_at->format('Y-m-d') : 'N/A' }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($application->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('seniors.id-application.view', $application->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('seniors.id-application.edit', $application->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="showDeleteModal('{{ $application->id }}', 'Application {{ $application->id }}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No ID applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>