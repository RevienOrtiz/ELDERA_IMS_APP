@include('tables.seniors', ['includeStylesOnly' => false])
<script>
  // Ensure the Benefits Applicants tab with Pension subtab is active on this page
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
      if (typeof showTab === 'function') {
        try { showTab('benefits-applicants'); } catch (e) { console.error('Failed to activate Benefits tab:', e); }
      }
      if (typeof showSubTab === 'function') {
        try { showSubTab('pension'); } catch (e) { console.error('Failed to activate Pension subtab:', e); }
      }

      // Override tab navigation to use routes
      const allBtn = document.querySelector('[onclick="showTab(\'all-seniors\')"]');
      const benefitsBtn = document.querySelector('[onclick="showTab(\'benefits-applicants\')"]');
      const idBtn = document.querySelector('[onclick="showTab(\'id-applicants\')"]');
      // Override subtab navigation to use routes as well
      const subExistingBtn = document.querySelector('[onclick="showSubTab(\'existing-senior\')"]');
      const subPensionBtn = document.querySelector('[onclick="showSubTab(\'pension\')"]');

      if (allBtn) allBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors') }}"; };
      if (benefitsBtn) benefitsBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.benefits') }}"; };
      if (idBtn) idBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.id-applications') }}"; };
      if (subExistingBtn) subExistingBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.benefits') }}"; };
      if (subPensionBtn) subPensionBtn.onclick = function (e) { e.preventDefault(); window.location.href = "{{ route('seniors.pension') }}"; };
    }, 0);
  });
</script>