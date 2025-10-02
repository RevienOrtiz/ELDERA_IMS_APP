@include('tables.seniors', ['includeStylesOnly' => false])
<script>
  // Ensure the ID Applicants tab is active when this wrapper is used
  document.addEventListener('DOMContentLoaded', function () {
    // Defer to allow included script definitions to load
    setTimeout(function () {
      if (typeof showTab === 'function') {
        try { showTab('id-applicants'); } catch (e) { console.error('Failed to activate ID Applicants tab:', e); }
      }

      // Override tab buttons to navigate to proper routes so datasets load
      const allBtn = document.querySelector('[onclick="showTab(\'all-seniors\')"]');
      const benefitsBtn = document.querySelector('[onclick="showTab(\'benefits-applicants\')"]');
      const idBtn = document.querySelector('[onclick="showTab(\'id-applicants\')"]');

      if (allBtn) allBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors') }}"; };
      if (benefitsBtn) benefitsBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.benefits') }}"; };
      if (idBtn) idBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.id-applications') }}"; };
    }, 0);
  });
</script>