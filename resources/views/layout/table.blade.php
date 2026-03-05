<div class="relative w-full max-h-[600px] overflow-y-auto overflow-x-auto px-2 sm:px-4">

  <!-- Center Watermark Logo -->
<div class="relative w-full overflow-x-auto w-full">
  <!-- Watermark -->
  <div class="table-watermark pointer-events-none">
    <img src="{{ asset('logo.png') }}"
     alt="Logo"
     class="w-64 sm:w-72 md:w-96 lg:w-[28rem]
            object-contain opacity-40">
    </div>

  <table class="w-full border-collapse table-auto relative z-10 bg-orange-50/30 rounded-lg shadow-sm"  id="evaluationTable">
    <thead class="bg-orange-100 sticky top-0 z-20">
      <tr>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">
          <input type="checkbox" id="selectAllCheckbox" class="select-all-checkbox">
        </th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">No.</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Supplier Name</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Purchase Order</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Evaluation Date</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Evaluator</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Department</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Evaluation Score</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Status</th>
        <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-orange-700 uppercase">Actions</th>
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-200 bg-transparent">
      <!-- Rows will be dynamically inserted here -->
    </tbody>
  </table>

</div>
<script>
    const currentUserRole = "{{ auth()->user()->role }}";
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#evaluationTable tbody');
    const table = document.getElementById('evaluationTable');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const calculateBtn = document.getElementById('calculateEvaluationsBtn');
    const downloadSummaryBtn = document.getElementById('DownloadcalculateEvaluationsSummaryBtn');
    const modal = document.getElementById('evaluationModal');
    const closeModalBtn = document.getElementById('closecalculateModalBtn');
    const modalContent = document.getElementById('modalContent');
    const searchInput = document.getElementById('searchInput');
    const departmentFilter = document.getElementById('departmentFilter');
    const startDateFilter = document.getElementById('startDateFilter');
    const endDateFilter = document.getElementById('endDateFilter');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    let evaluationsData = [];
    let filteredData = [];

    // --- Fetch Evaluations ---
    async function fetchEvaluations() {
        try {
            const response = await fetch("{{ route('evaluation.list') }}");
            const data = await response.json();
            evaluationsData = data.evaluations ?? [];
            filteredData = [...evaluationsData];
            renderTable(filteredData);

            const loadingModal = document.getElementById('loadingModal');
            if (loadingModal) {
                loadingModal.classList.add('hidden');
                setTimeout(() => loadingModal.style.display = 'none', 800);
            }
        } catch (err) {
            console.error(err);
            Swal.fire('Error!', 'Failed to load evaluations.', 'error');
        }
    }

    // --- Expandable Cell ---
    function expandableCell(text) {
        const safeText = text || 'PENDING';
        const isLong = safeText.length > 25;
        return `
            <div class="${isLong ? 'truncate-text' : ''} text-wrapper">
                <strong>${safeText}</strong>
            </div>
            ${isLong ? `<span class="expand-btn cursor-pointer text-blue-500 text-sm">Expand</span>` : ''}
        `;
    }

    // --- Calculate weighted score helper ---
    function calculateWeightedScore(criteria = []) {
        const scoreA = criteria.find(c => c.criteria_id === 1)?.number_rating ?? null;
        const scoreB = criteria.find(c => c.criteria_id === 2)?.number_rating ?? null;
        const scoreC = criteria.find(c => c.criteria_id === 3)?.number_rating ?? null;
        const scoreD = criteria.find(c => c.criteria_id === 4)?.number_rating ?? null;
        if ([scoreA, scoreB, scoreC, scoreD].some(v => v === null)) return null;
        return ((scoreA*5) + (scoreB*7.5) + (scoreC*6.25) + (scoreD*6.25)).toFixed(2);
    }

    // --- Determine Status Helper (NEW CENTRALIZED LOGIC) ---
    function determineStatus(evaluation) {
        const weightedScore = calculateWeightedScore(evaluation.criteria_scores);
        const hasIncomplete = weightedScore === null;

        const approvals = evaluation.digital_approvals ?? [];
        const hasHeadApproval = approvals.some(a =>
            a.role && a.role.toLowerCase() === 'head'
        );

        if (hasIncomplete) {
            return {
                status: 'PENDING',
                statusClass: 'bg-yellow-200 text-yellow-700',
                scoreClass: ''
            };
        }

        if (hasHeadApproval) {
            return {
                status: 'Approved',
                statusClass: 'bg-green-200 text-green-700',
                scoreClass: 'bg-green-100 text-green-800 rounded'
            };
        }

        return {
            status: 'For Office Head Review',
            statusClass: 'bg-red-200 text-red-700',
            scoreClass: 'bg-red-100 text-red-800 rounded'
        };
    }

  // --- Render Table ---
function renderTable(data) {
    tableBody.innerHTML = '';

    data.forEach((evaluation, index) => {
        const weightedScore = calculateWeightedScore(evaluation.criteria_scores);
        const hasIncomplete = weightedScore === null;
        const evaluator = evaluation.digital_approvals?.[0]?.full_name ?? null;

        // --- Determine status using Head Review logic ---
        const { status, statusClass, scoreClass } = (() => {
            const approvals = evaluation.digital_approvals ?? [];
            const hasHeadApproval = approvals.some(a => a.role && a.role.toLowerCase() === 'head');

            if (hasIncomplete) {
                return {
                    status: 'PENDING',
                    statusClass: 'bg-yellow-200 text-yellow-700',
                    scoreClass: ''
                };
            }

            if (hasHeadApproval) {
                return {
                    status: 'Approved',
                    statusClass: 'bg-green-200 text-green-700',
                    scoreClass: 'bg-green-100 text-green-800 rounded'
                };
            }

            return {
                status: 'For Office Head Review',
                statusClass: 'bg-red-200 text-red-700',
                scoreClass: 'bg-red-100 text-red-800 rounded'
            };
        })();

        // --- Conditional Delete Option ---
        const deleteOption = currentUserRole !== 'end_user'
            ? `<option value="delete" class="hidden">Delete</option>`
            : '';

        // --- Table Row with Expandable Cells ---
        const row = `
            <tr class="hover:bg-orange-100 transition">
                <td class="px-6 py-4"><input type="checkbox" class="rowCheckbox" value="${evaluation.id}"></td>
                <td class="px-6 py-4">${index + 1}</td>
                <td class="px-6 py-4">${expandableCell(evaluation.supplier_name)}</td>
                <td class="px-6 py-4">${expandableCell(evaluation.po_no)}</td>
                <td class="px-6 py-4">${evaluation.date_evaluation || 'PENDING'}</td>
                <td class="px-6 py-4">${expandableCell(evaluator)}</td>
                <td class="px-6 py-4">${expandableCell(evaluation.department)}</td>
<td class="px-6 py-4 font-semibold ${hasIncomplete
    ? ''
    : weightedScore >= 60
        ? 'bg-green-100 text-green-800 rounded'
        : 'bg-red-100 text-red-800 rounded'}">
    <strong>${hasIncomplete ? 'PENDING' : `${weightedScore}%`}</strong>
</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded ${statusClass}">
                        ${status}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <select data-id="${evaluation.id}" class="evaluationAction w-36 bg-orange-500 text-white font-semibold px-3 py-2 rounded-lg text-sm shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-orange-300 hover:bg-orange-600 transition">
                        <option value="" selected disabled>Action</option>
                        <option value="review">Review</option>
                        <option value="edit">Edit</option>
                        <option value="link">Copy Link</option>
                        ${deleteOption}
                        <option value="download">Download PDF</option>
                    </select>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    updateSelectedRows();
}
        // --- Expand/Collapse ---
    tableBody.addEventListener('click', function(e) {
        if (!e.target.classList.contains('expand-btn')) return;
        const wrapper = e.target.previousElementSibling;
        if (wrapper.classList.contains('truncate-text')) {
            wrapper.classList.remove('truncate-text'); wrapper.classList.add('expanded-text'); e.target.textContent = "Collapse";
        } else {
            wrapper.classList.add('truncate-text'); wrapper.classList.remove('expanded-text'); e.target.textContent = "Expand";
        }
    });

    // --- Combined Filtering ---
    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const departmentValue = departmentFilter.value;
        const startDate = startDateFilter.value;
        const endDate = endDateFilter.value;

        filteredData = evaluationsData.filter(e => {
            const weightedScore = calculateWeightedScore(e.criteria_scores);
            const { status } = determineStatus(e);

            const combinedText = `
                ${e.supplier_name ?? ''}
                ${e.po_no ?? ''}
                ${e.date_evaluation ?? ''}
                ${e.digital_approvals?.[0]?.full_name ?? ''}
                ${e.office_name ?? ''}
                ${status}
                ${weightedScore ?? ''}
            `.toLowerCase();

            const matchesText = combinedText.includes(searchText);

            let matchesDepartment = true;
            if (departmentValue) {
                if (
                    departmentValue === 'PENDING' ||
                    departmentValue === 'Approved' ||
                    departmentValue === 'For Office Head Review'
                ) {
                    matchesDepartment = status === departmentValue;
                } else {
                    matchesDepartment = e.office_name === departmentValue;
                }
            }

            const evalDate = e.date_evaluation ? new Date(e.date_evaluation) : null;
            const afterStart = !startDate || (evalDate && evalDate >= new Date(startDate));
            const beforeEnd = !endDate || (evalDate && evalDate <= new Date(endDate));

            return matchesText && matchesDepartment && afterStart && beforeEnd;
        });

        renderTable(filteredData);
    }

    searchInput.addEventListener('input', filterTable);
    departmentFilter.addEventListener('change', filterTable);
    startDateFilter.addEventListener('change', filterTable);
    endDateFilter.addEventListener('change', filterTable);

    clearFiltersBtn.addEventListener('click', () => {
        searchInput.value = '';
        departmentFilter.value = '';
        startDateFilter.value = '';
        endDateFilter.value = '';
        filteredData = [...evaluationsData];
        renderTable(filteredData);
    });

        // -----------------------------
    // DROPDOWN ACTIONS WITH LOADING
    // -----------------------------
//     tableBody.addEventListener('change', async function(e) {
//         if (!e.target.classList.contains('evaluationAction')) return;
//         const evaluationId = e.target.dataset.id;
//         const action = e.target.value;
//         if (!evaluationId) return;

//         Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

//         try {
//             if (action === 'review') {
//                 await viewEvaluation(evaluationId);
//             } else if (action === 'edit') {
//                 await updateEvaluation(evaluationId);
//             } else if (action === 'download') {
//                 // Open PDF download in new tab
//                 window.open(`/evaluations/${evaluationId}/download`, '_blank');
//             } else if (action === 'link') {
//             // -----------------------
//             // Copy Head Review Link
//             // -----------------------
//             // Build absolute URL
//             const origin = window.location.origin;
//             const reviewUrl = `${origin}/evaluation/head-review/${evaluationId}`;

//             Swal.fire({
//                 icon: 'info',
//                 title: 'Head Review Link',
//                 html: `
//                     <input type="text" id="copyEvalLink" class="swal2-input" value="${reviewUrl}" readonly>
//                     <button id="copyLinkBtn" class="swal2-confirm swal2-styled" style="margin-top:5px;">Copy Link</button>
//                 `,
//                 showConfirmButton: false,
//                 showCloseButton: true,
//                 didOpen: () => {
//                     const copyBtn = document.getElementById('copyLinkBtn');
//                     copyBtn.addEventListener('click', () => {
//                         const linkInput = document.getElementById('copyEvalLink');
//                         linkInput.select();
//                         linkInput.setSelectionRange(0, 99999); // For mobile devices
//                         document.execCommand('copy');
//                         Swal.fire('Copied!', 'Head Review link copied to clipboard.', 'success');
//                     });
//                 }
//             });
//         }
//     } catch (err) {
//         console.error(err);
//         Swal.fire('Error!', err.message || 'Action failed.', 'error');
//     } finally {
//         Swal.close();
//         e.target.value = ''; // Reset select
//     }
// });
//             else if (action === 'delete') {
//                 if (confirm('Delete this evaluation?')) {
//                     const res = await fetch(`/evaluations/${evaluationId}`, {
//                         method: 'DELETE',
//                         headers: {
//                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//                             'Accept': 'application/json'
//                         }
//                     });
//                     const data = await res.json();
//                     if (data.success) fetchEvaluations();
//                     else alert(data.message || 'Delete failed.');
//                 }
//             }
//         } catch (err) {
//             console.error(err);
//             alert('Action failed: ' + err.message);
//         } finally {
//             Swal.close();
//             e.target.value = '';
//         }
//     });

tableBody.addEventListener('change', async function(e) {
    if (!e.target.classList.contains('evaluationAction')) return;

    const evaluationId = e.target.dataset.id;
    const action = e.target.value;
    if (!evaluationId) return;

    if (action !== 'link') {
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    }

    try {
        const evaluation = filteredData.find(ev => ev.id == evaluationId);
        if (!evaluation) throw new Error('Evaluation not found');

        if (action === 'review') {
            await viewEvaluation(evaluationId);

        } else if (action === 'edit') {
            await updateEvaluation(evaluationId);

        } else if (action === 'download') {
            window.open(`/evaluations/${evaluationId}/download`, '_blank');

        } else if (action === 'delete') {

            if (confirm('Delete this evaluation?')) {
                const res = await fetch(`/evaluations/${evaluationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (data.success) fetchEvaluations();
                else alert(data.message || 'Delete failed.');
            }

        } else if (action === 'link') {

            if (!evaluation.head_review_token) {
                Swal.fire('Error!', 'No active Head review link available.', 'error');
                return;
            }

            const origin = window.location.origin;
            const reviewUrl = `${origin}/evaluation/head-review/${evaluation.head_review_token}`;
            const reviewCode = evaluation.head_review_code ?? '';

Swal.fire({
    title: 'Head Review Access',
    width: 520,
    showConfirmButton: false,
    showCloseButton: true,
    allowOutsideClick: true,
    html: `
    <style>

        .access-container{
            text-align:left;
            font-family:system-ui;
        }

        .access-card{
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:10px;
            padding:14px;
            margin-top:12px;
            transition:all .2s ease;
        }

        .access-card:hover{
            border-color:#fb923c;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
        }

        .access-label{
            font-weight:600;
            font-size:13px;
            color:#374151;
            margin-bottom:6px;
            display:block;
        }

        .access-input{
            width:100%;
            border:1px solid #d1d5db;
            border-radius:6px;
            padding:8px 10px;
            font-size:13px;
            background:white;
        }

        .copy-btn{
            margin-top:10px;
            width:100%;
            padding:9px;
            border:none;
            border-radius:7px;
            cursor:pointer;
            font-weight:600;
            font-size:13px;
            color:white;
            background:linear-gradient(135deg,#fb923c,#f97316);
            transition:all .25s ease;
        }

        .copy-btn:hover{
            transform:translateY(-1px);
            box-shadow:0 6px 14px rgba(0,0,0,.15);
        }

        .copy-btn.copied{
            background:linear-gradient(135deg,#22c55e,#16a34a);
            transform:scale(1.05);
        }

    </style>

    <div class="access-container">

        <div class="access-card">
            <span class="access-label">🔗 Review Link</span>
            <input type="text" id="copyEvalLink" class="access-input" value="${reviewUrl}" readonly>
            <button id="copyLinkBtn" class="copy-btn">Copy Link</button>
        </div>

        <div class="access-card">
            <span class="access-label">🔐 Review Code</span>
            <input type="text" id="copyEvalCode" class="access-input" value="${reviewCode}" readonly>
            <button id="copyCodeBtn" class="copy-btn">Copy Code</button>
        </div>

    </div>
    `,
    didOpen: () => {

        function animateCopy(button, text){

            navigator.clipboard.writeText(text);

            const original = button.innerHTML;

            button.classList.add('copied');
            button.innerHTML = "✔ Copied";

            setTimeout(()=>{
                button.classList.remove('copied');
                button.innerHTML = original;
            },1600);
        }

        document.getElementById('copyLinkBtn')
            .addEventListener('click', (e)=>{
                animateCopy(e.target, reviewUrl);
            });

        document.getElementById('copyCodeBtn')
            .addEventListener('click', (e)=>{
                animateCopy(e.target, reviewCode);
            });

    }
});
        }

    } catch (err) {
        console.error(err);
        Swal.fire('Error!', err.message || 'Action failed.', 'error');

    } finally {
        e.target.value = '';
        if (action !== 'link') Swal.close();
    }
});


    // --- Track Selection ---
    function updateSelectedRows() {
        const selected = table.querySelectorAll('tbody .rowCheckbox:checked');
        calculateBtn.classList.toggle('hidden', selected.length < 2);
        downloadSummaryBtn.classList.toggle('hidden', selected.length < 1);
    }

    table.addEventListener('change', function(e) {
        if (e.target.classList.contains('rowCheckbox')) updateSelectedRows();
    });

    selectAllCheckbox.addEventListener('change', function () {
        table.querySelectorAll('tbody .rowCheckbox').forEach(cb => cb.checked = selectAllCheckbox.checked);
        updateSelectedRows();
    });

    // --- Calculate Modal ---
    calculateBtn.addEventListener('click', function () {
        const selectedCheckboxes = table.querySelectorAll('tbody .rowCheckbox:checked');
        const rowsData = [], selectedScores = [];

        selectedCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            const supplierName = row.querySelector('td:nth-child(3)').textContent.trim() || 'PENDING';
            const poNo = row.querySelector('td:nth-child(4)').textContent.trim() || 'PENDING';
            const evalDate = row.querySelector('td:nth-child(5)').textContent.trim() || 'PENDING';
            const department = row.querySelector('td:nth-child(7)').textContent.trim() || 'PENDING';
            const scoreTd = row.querySelector('td:nth-child(8)');
            let scoreText = scoreTd.textContent.trim();
            let score = scoreText.toUpperCase() !== 'PENDING' ? parseFloat(scoreText) : null;
            if (score !== null && !isNaN(score)) selectedScores.push(score);

            rowsData.push({supplierName, poNo, evalDate, department, score: scoreText});
        });

        let tableRows = rowsData.map(d => `
            <tr class="border-b">
                <td class="px-4 py-2">${d.supplierName}</td>
                <td class="px-4 py-2">${d.poNo}</td>
                <td class="px-4 py-2">${d.evalDate}</td>
                <td class="px-4 py-2">${d.department}</td>
                <td class="px-4 py-2 font-semibold">${d.score}</td>
            </tr>`).join('');

        const average = selectedScores.length
            ? (selectedScores.reduce((a,b)=>a+b,0)/selectedScores.length).toFixed(2)
            : 'N/A';

// ==========================
// SIMPLE ANALYTICS OBJECT
// ==========================
window.evalAnalytics = window.evalAnalytics || {
    totalRowsViewed: 0,
    lastAverage: 0,
    lastOpened: null
};

// Update analytics
evalAnalytics.totalRowsViewed += rowsData.length;
evalAnalytics.lastAverage = average;
evalAnalytics.lastOpened = new Date().toISOString();

console.log("Evaluation Analytics:", evalAnalytics);


// ==========================
// MODAL CONTENT
// ==========================
modalContent.innerHTML = `
<style>

.calc-header{
    background:linear-gradient(135deg,#fb923c,#f97316);
    color:white;
    padding:14px;
    border-radius:10px 10px 0 0;
    font-weight:600;
    font-size:16px;
}

.calc-table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

.calc-table th{
    background:#f3f4f6;
    padding:10px;
    text-align:left;
    font-weight:600;
}

.calc-table td{
    padding:9px;
    border-bottom:1px solid #eee;
}

.calc-table tbody tr{
    transition:all .2s ease;
}

.calc-table tbody tr:hover{
    background:#fff7ed;
    transform:scale(1.01);
}

/* Colored badges for score */
.score-badge{
    padding:4px 8px;
    border-radius:6px;
    font-weight:600;
    text-align:center;
    display:inline-block;
    min-width:50px;
}

.score-low{
    background:#ef4444;  /* red */
    color:white;
}

.score-medium{
    background:#f59e0b; /* orange */
    color:white;
}

.score-high{
    background:#22c55e; /* green */
    color:white;
}

.avg-box{
    margin-top:15px;
    padding:12px;
    border-radius:8px;
    font-size:18px;
    font-weight:700;
    text-align:center;
    animation:scorePop .4s ease;
}

@keyframes scorePop{
    0%{transform:scale(.9);opacity:0;}
    100%{transform:scale(1);opacity:1;}
}

</style>

<div class="calc-header">
📊 Evaluation Summary
</div>


<div class="stats-row" style="display:flex;gap:10px;margin-bottom:15px;">
    <div class="stat-card" style="flex:1;background:#f9fafb;border-radius:8px;padding:10px;text-align:center;font-size:13px;border:1px solid #eee;">
        Records
        <strong>${rowsData.length}</strong>
    </div>

    <div class="stat-card" style="flex:1;background:#f9fafb;border-radius:8px;padding:10px;text-align:center;font-size:13px;border:1px solid #eee;">
        Average Score
        <strong>${average}%</strong>
    </div>
</div>

<div class="overflow-x-auto max-h-96">
<table class="calc-table">
<thead>
<tr>
<th>Supplier Name</th>
<th>PO Number</th>
<th>Evaluation Date</th>
<th>Department</th>
<th>Score</th>
</tr>
</thead>
<tbody>
${rowsData.map(d => {
    let scoreClass = "score-high";
    let numericScore = parseFloat(d.score);
    if(isNaN(numericScore)) numericScore = 0;

    if(numericScore < 60) scoreClass = "score-low";
    else if(numericScore >= 60 && numericScore < 75) scoreClass = "score-medium";

    return `
    <tr>
        <td>${d.supplierName}</td>
        <td>${d.poNo}</td>
        <td>${d.evalDate}</td>
        <td>${d.department}</td>
        <td><span class="score-badge ${scoreClass}">${d.score}</span></td>
    </tr>
    `;
}).join('')}
</tbody>
</table>
</div>

<div class="avg-box" style="background:${average < 60 ? '#ef4444' : average < 75 ? '#f59e0b' : '#22c55e'}; color:white;">
⭐ Average Evaluation Score: ${average}%
</div>
`;
        modal.classList.remove('hidden');
    });

    // --- Download Summary PDF ---
    downloadSummaryBtn.addEventListener('click', function () {
        const selectedIds = Array.from(table.querySelectorAll('tbody .rowCheckbox:checked'))
                                 .map(cb => parseInt(cb.value))
                                 .filter(id => !isNaN(id));

        if (!selectedIds.length) return alert('Please select at least one evaluation.');

        const params = new URLSearchParams();
        selectedIds.forEach(id => params.append('ids[]', id));

        const url = `{{ route('evaluations.summary.download') }}?${params.toString()}`;
        window.open(url, '_blank');
    });

    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));

    fetchEvaluations();
});
</script>





{{-- Fetch Evaluations & Populate Table --}}
{{-- <script>
document.addEventListener('DOMContentLoaded', function () {

    let evaluationsData = []; // store fetched evaluations

    const tableBody = document.querySelector('#evaluationTable tbody');
    const searchInput = document.getElementById('searchInput');
    const departmentFilter = document.getElementById('departmentFilter');
    const startDateFilter = document.getElementById('startDateFilter');
    const endDateFilter = document.getElementById('endDateFilter');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    fetchEvaluations();

async function fetchEvaluations() {
    try {
        const response = await fetch("{{ route('evaluation.list') }}");
        const data = await response.json();

        // Use data.evaluations instead of data
        evaluationsData = data.evaluations ?? [];
        renderTable(evaluationsData);

        // Fade out and hide loading modal
        const loadingModal = document.getElementById('loadingModal');
        loadingModal.classList.add('hidden');
        setTimeout(() => {
            loadingModal.style.display = 'none';
        }, 800);
    } catch (error) {
        console.error('Error fetching evaluations:', error);
        Swal.fire('Error!', 'Failed to load evaluations.', 'error');
    }
}

    function renderTable(data) {
        tableBody.innerHTML = '';
        data.forEach((evaluation, index) => {

            const criteria = evaluation.criteria_scores || [];

            const scoreA = criteria.find(c => c.criteria_id === 1)?.number_rating ?? null;
            const scoreB = criteria.find(c => c.criteria_id === 2)?.number_rating ?? null;
            const scoreC = criteria.find(c => c.criteria_id === 3)?.number_rating ?? null;
            const scoreD = criteria.find(c => c.criteria_id === 4)?.number_rating ?? null;

            const hasIncompleteCriteria = [scoreA, scoreB, scoreC, scoreD].some(v => v === null);
            const weightedScore = ((scoreA||0)*5 + (scoreB||0)*7.5 + (scoreC||0)*6.25 + (scoreD||0)*6.25).toFixed(2);

            const evaluator = evaluation.digital_approvals?.[0]?.full_name ?? null;

            let status = 'PENDING';
            let statusClass = 'bg-yellow-200 text-yellow-700';

            if (!hasIncompleteCriteria && evaluator) {
                if (weightedScore >= 60) {
                    status = 'Approved';
                    statusClass = 'bg-green-200 text-green-700';
                } else {
                    status = 'Fail / For Office Head Review';
                    statusClass = 'bg-red-200 text-red-700';
                }
            }

            const row = `
                <tr class="hover:bg-orange-100 transition">
                    <td class="px-6 py-4"><input type="checkbox" class="rowCheckbox" value="${evaluation.id}"></td>
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4"><strong>${makeExpandable(evaluation.supplier_name || 'PENDING')}</strong></td>
                    <td class="px-6 py-4"><strong>${makeExpandable(evaluation.po_no || 'PENDING')}</strong></td>
                    <td class="px-6 py-4">${makeExpandable(evaluation.date_evaluation || 'PENDING')}</td>
                    <td class="px-6 py-4"><strong>${makeExpandable(evaluator || 'PENDING')}</strong></td>
                    <td class="px-6 py-4">${makeExpandable(evaluation.department || 'PENDING')}</td>
<td class="px-6 py-4 font-semibold ${hasIncompleteCriteria ? 'PENDING' : weightedScore >= 60 ? 'bg-green-100 text-green-800 rounded' : 'bg-red-100 text-red-800 rounded'}"><strong> ${hasIncompleteCriteria ? 'PENDING' : ${weightedScore}%}</strong> </td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded ${statusClass}">${status}</span></td>
                    <td class="px-6 py-4">
                        <select data-id="${evaluation.id}" class="evaluationAction w-36 bg-orange-500 text-white font-semibold px-3 py-2 rounded-lg text-sm shadow-sm
                             focus:outline-none focus:ring-2 focus:ring-orange-300 hover:bg-orange-600 transition">
                            <option value="" selected disabled>Action</option>
                            <option value="review">Review</option>
                            <option value="edit">Edit</option>
                            <option value="delete">Delete</option>
                            <option value="download">Download PDF</option>
                        </select>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }
    function makeExpandable(text) {
    const value = text || 'PENDING';
    const isLong = value.length > 25;

    return `
        <div class="${isLong ? 'truncate-text' : ''}">
            <strong>${value}</strong>
        </div>
        ${isLong ? `<span class="expand-btn">Expand</span>` : ''}
    `;
    }

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const department = departmentFilter.value;
        const startDate = startDateFilter.value;
        const endDate = endDateFilter.value;

        const filtered = evaluationsData.filter(e => {
            const combinedText = `
                ${e.supplier_name ?? 'PENDING'}
                ${e.po_no ?? 'PENDING'}
                ${e.date_evaluation ?? 'PENDING'}
                ${e.digital_approvals?.[0]?.full_name ?? 'PENDING'}
                ${e.office_name ?? 'PENDING'}
                ${calculateWeightedScore(e.criteria_scores) ?? 'PENDING'}
            `.toLowerCase();

            const matchesText = combinedText.includes(searchText);
            const matchesTextStatus = combinedText.includes(searchText);
            const matchesDepartment = !department || e.office_name === department;

            const evalDate = e.date_evaluation ? new Date(e.date_evaluation) : null;
            const afterStart = !startDate || (evalDate && evalDate >= new Date(startDate));
            const beforeEnd = !endDate || (evalDate && evalDate <= new Date(endDate));

            return matchesText && matchesDepartment && afterStart && beforeEnd;
        });

        renderTable(filtered);
    }

    function calculateWeightedScore(criteria = []) {
        let scoreA = criteria.find(c => c.criteria_id === 1)?.number_rating ?? null;
        let scoreB = criteria.find(c => c.criteria_id === 2)?.number_rating ?? null;
        let scoreC = criteria.find(c => c.criteria_id === 3)?.number_rating ?? null;
        let scoreD = criteria.find(c => c.criteria_id === 4)?.number_rating ?? null;

        if ([scoreA, scoreB, scoreC, scoreD].some(v => v === null)) return null;
        return ((scoreA*5) + (scoreB*7.5) + (scoreC*6.25) + (scoreD*6.25)).toFixed(2);
    }

    clearFiltersBtn.addEventListener('click', function () {
        searchInput.value = '';
        startDateFilter.value = '';
        endDateFilter.value = '';
        renderTable(evaluationsData);
    });

    searchInput.addEventListener('input', filterTable);
    startDateFilter.addEventListener('change', filterTable);
    endDateFilter.addEventListener('change', filterTable);

    // -----------------------------
    // DROPDOWN ACTIONS WITH LOADING
    // -----------------------------
    tableBody.addEventListener('change', async function(e) {
        if (!e.target.classList.contains('evaluationAction')) return;
        const evaluationId = e.target.dataset.id;
        const action = e.target.value;
        if (!evaluationId) return;

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
            if (action === 'review') {
                await viewEvaluation(evaluationId);
            } else if (action === 'edit') {
                await updateEvaluation(evaluationId);
            } else if (action === 'download') {
                // Open PDF download in new tab
                window.open(`/evaluations/${evaluationId}/download`, '_blank');
            } else if (action === 'delete') {
                if (confirm('Delete this evaluation?')) {
                    const res = await fetch(`/evaluations/${evaluationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (data.success) fetchEvaluations();
                    else alert(data.message || 'Delete failed.');
                }
            }
        } catch (err) {
            console.error(err);
            alert('Action failed: ' + err.message);
        } finally {
            Swal.close();
            e.target.value = '';
        }
    });
// --- Expand/Collapse Click Handler ---
tableBody.addEventListener('click', function(e) {
    if (!e.target.classList.contains('expand-btn')) return;

    // The div containing the text is the previous sibling of the button
    const wrapper = e.target.previousElementSibling;
    if (!wrapper) return;

    if (wrapper.classList.contains('truncate-text')) {
        wrapper.classList.remove('truncate-text');
        wrapper.classList.add('expanded-text');
        e.target.textContent = "Collapse";
    } else {
        wrapper.classList.add('truncate-text');
        wrapper.classList.remove('expanded-text');
        e.target.textContent = "Expand";
    }
});
});
</script> --}}




{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('evaluationTable');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const calculateBtn = document.getElementById('calculateEvaluationsBtn');
    const downloadSummaryBtn = document.getElementById('DownloadcalculateEvaluationsSummaryBtn');
    const modal = document.getElementById('evaluationModal');
    const closeModalBtn = document.getElementById('closecalculateModalBtn');
    const modalContent = document.getElementById('modalContent');

    // --- Track selected rows and toggle buttons ---
    function updateSelectedRows() {
        const selectedCheckboxes = table.querySelectorAll('tbody .rowCheckbox:checked');
        const hasMultiple = selectedCheckboxes.length >= 2;

        calculateBtn.classList.toggle('hidden', !hasMultiple);
        downloadSummaryBtn.classList.toggle('hidden', selectedCheckboxes.length < 1); // show if at least 1 selected
    }

    // Event: row checkbox change
    table.addEventListener('change', function (e) {
        if (e.target.classList.contains('rowCheckbox')) {
            updateSelectedRows();
        }
    });

    // Event: select all checkbox
    selectAllCheckbox.addEventListener('change', function () {
        const checkboxes = table.querySelectorAll('tbody .rowCheckbox');
        checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        updateSelectedRows();
    });

    // --- Calculate selected evaluations (existing modal) ---
    calculateBtn.addEventListener('click', function () {
        const selectedCheckboxes = table.querySelectorAll('tbody .rowCheckbox:checked');
        const selectedScores = [];
        const rowsData = [];

        selectedCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            const supplierName = row.querySelector('td:nth-child(3)').textContent.trim() || 'PENDING';
            const poNo = row.querySelector('td:nth-child(4)').textContent.trim() || 'PENDING';
            const evalDate = row.querySelector('td:nth-child(5)').textContent.trim() || 'PENDING';
            const department = row.querySelector('td:nth-child(7)').textContent.trim() || 'PENDING';
            const scoreTd = row.querySelector('td:nth-child(8)');
            let scoreText = scoreTd.textContent.trim();
            let score = scoreText.toUpperCase() !== 'PENDING' ? parseFloat(scoreText) : null;
            if (score !== null && !isNaN(score)) selectedScores.push(score);

            rowsData.push({
                supplierName,
                poNo,
                evalDate,
                department,
                score: scoreText
            });
        });

        // Build modal content
        let tableRows = rowsData.map(d => `
            <tr class="border-b">
                <td class="px-4 py-2">${d.supplierName}</td>
                <td class="px-4 py-2">${d.poNo}</td>
                <td class="px-4 py-2">${d.evalDate}</td>
                <td class="px-4 py-2">${d.department}</td>
                <td class="px-4 py-2 font-semibold">${d.score}</td>
            </tr>
        `).join('');

        const average = selectedScores.length
            ? (selectedScores.reduce((a, b) => a + b, 0) / selectedScores.length).toFixed(2)
            : 'N/A';

// ==========================
// SIMPLE ANALYTICS OBJECT
// ==========================
window.evalAnalytics = window.evalAnalytics || {
    totalRowsViewed: 0,
    lastAverage: 0,
    lastOpened: null
};

// Update analytics
evalAnalytics.totalRowsViewed += rowsData.length;
evalAnalytics.lastAverage = average;
evalAnalytics.lastOpened = new Date().toISOString();

console.log("Evaluation Analytics:", evalAnalytics);


// ==========================
// MODAL CONTENT
// ==========================
modalContent.innerHTML = `
<style>

.calc-header{
    background:linear-gradient(135deg,#fb923c,#f97316);
    color:white;
    padding:14px;
    border-radius:10px 10px 0 0;
    font-weight:600;
    font-size:16px;
}

.calc-table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

.calc-table th{
    background:#f3f4f6;
    padding:10px;
    text-align:left;
    font-weight:600;
}

.calc-table td{
    padding:9px;
    border-bottom:1px solid #eee;
}

.calc-table tbody tr{
    transition:all .2s ease;
}

.calc-table tbody tr:hover{
    background:#fff7ed;
    transform:scale(1.01);
}

/* Colored badges for score */
.score-badge{
    padding:4px 8px;
    border-radius:6px;
    font-weight:600;
    text-align:center;
    display:inline-block;
    min-width:50px;
}

.score-low{
    background:#ef4444;  /* red */
    color:white;
}

.score-medium{
    background:#f59e0b; /* orange */
    color:white;
}

.score-high{
    background:#22c55e; /* green */
    color:white;
}

.avg-box{
    margin-top:15px;
    padding:12px;
    border-radius:8px;
    font-size:18px;
    font-weight:700;
    text-align:center;
    animation:scorePop .4s ease;
}

@keyframes scorePop{
    0%{transform:scale(.9);opacity:0;}
    100%{transform:scale(1);opacity:1;}
}

</style>

<div class="calc-header">
📊 Evaluation Summary
</div>


<div class="stats-row" style="display:flex;gap:10px;margin-bottom:15px;">
    <div class="stat-card" style="flex:1;background:#f9fafb;border-radius:8px;padding:10px;text-align:center;font-size:13px;border:1px solid #eee;">
        Records
        <strong>${rowsData.length}</strong>
    </div>

    <div class="stat-card" style="flex:1;background:#f9fafb;border-radius:8px;padding:10px;text-align:center;font-size:13px;border:1px solid #eee;">
        Average Score
        <strong>${average}%</strong>
    </div>
</div>

<div class="overflow-x-auto max-h-96">
<table class="calc-table">
<thead>
<tr>
<th>Supplier Name</th>
<th>PO Number</th>
<th>Evaluation Date</th>
<th>Department</th>
<th>Score</th>
</tr>
</thead>
<tbody>
${rowsData.map(d => {
    let scoreClass = "score-high";
    let numericScore = parseFloat(d.score);
    if(isNaN(numericScore)) numericScore = 0;

    if(numericScore < 60) scoreClass = "score-low";
    else if(numericScore >= 60 && numericScore < 75) scoreClass = "score-medium";

    return `
    <tr>
        <td>${d.supplierName}</td>
        <td>${d.poNo}</td>
        <td>${d.evalDate}</td>
        <td>${d.department}</td>
        <td><span class="score-badge ${scoreClass}">${d.score}</span></td>
    </tr>
    `;
}).join('')}
</tbody>
</table>
</div>

<div class="avg-box" style="background:${average < 60 ? '#ef4444' : average < 75 ? '#f59e0b' : '#22c55e'}; color:white;">
⭐ Average Evaluation Score: ${average}%
</div>
`;

        modal.classList.remove('hidden');
    });

    // --- Download Summary PDF ---
    downloadSummaryBtn.addEventListener('click', function () {
        const selectedIds = Array.from(table.querySelectorAll('tbody .rowCheckbox:checked'))
                                 .map(cb => cb.value);

        if (!selectedIds.length) {
            alert('Please select at least one evaluation to download.');
            return;
        }

        const params = new URLSearchParams();
        selectedIds.forEach(id => params.append('ids[]', id));

        const url = `{{ route('evaluations.summary.download') }}?${params.toString()}`;
        window.open(url, '_blank');
    });

    // Close modal
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
});
</script> --}}



<style>
.truncate-text {
    max-width: 180px;        /* adjust width */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.expanded-text {
    white-space: normal;
    word-break: break-word;
}

.expand-btn {
    color: #0a0092; /* orange */
    cursor: pointer;
    font-size: 12px;
    margin-left: 5px;
    font-weight: 600;
}
</style>
