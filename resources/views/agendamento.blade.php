<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - Studio Franciele Cesario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.pwa-head')
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .video-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: -1; }
        .overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 0; }
        .agendamento-container { position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .card { background: linear-gradient(180deg, rgba(255, 248, 250, 0.92), rgba(255, 237, 243, 0.88)); border-radius: 16px; box-shadow: 0 10px 30px rgba(122, 72, 86, 0.18); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(222, 168, 184, 0.28); color: #3a2330; }
        .wizard-steps { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; row-gap: 10px; }
        .step { display: flex; align-items: center; gap: 8px; font-size: 0.92rem; color: rgba(58,35,48,0.68); }
        .step-indicator { width: 32px; height: 32px; border-radius: 50%; background: rgba(185, 111, 134, 0.18); color: #3a2330; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        .step.active { color: #3a2330; }
        .step.active .step-indicator { background: linear-gradient(135deg, #b96f86, #d8a2b4); color: #fff; }
        .step-bar { flex: 1; height: 2px; background: rgba(185, 111, 134, 0.16); margin: 0 12px; min-width: 24px; }
        .step-bar.active { background: linear-gradient(90deg, #b96f86, #d8a2b4); }
        .wizard-step { display: none; }
        .wizard-step.active { display: block; }
        .wizard-actions { display: flex; gap: 0.75rem; }
        .summary-panel {
            background: linear-gradient(180deg, rgba(255, 251, 252, 0.96), rgba(252, 233, 239, 0.96));
            border: 1px solid rgba(217, 157, 175, 0.22);
            border-radius: 14px;
            padding: 1rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }
        .summary-heading {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            margin-bottom: 1rem;
        }
        .summary-kicker {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(122, 72, 86, 0.62);
        }
        .summary-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #3a2330;
        }
        .summary-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 0.65rem 0;
        }
        .summary-row + .summary-row {
            border-top: 1px solid rgba(31, 31, 31, 0.08);
        }
        .summary-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.84rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: rgba(122, 72, 86, 0.72);
            min-width: 92px;
        }
        .summary-icon {
            width: 24px;
            height: 24px;
            flex: 0 0 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 10px rgba(122, 72, 86, 0.10);
        }
        .summary-icon svg {
            width: 12px;
            height: 12px;
            display: block;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .summary-icon.time { background: linear-gradient(135deg, rgba(143, 77, 98, 0.88), rgba(208, 138, 160, 0.72)); }
        .summary-icon.service { background: linear-gradient(135deg, rgba(199, 135, 159, 0.82), rgba(226, 181, 196, 0.68)); }
        .summary-icon.professional { background: linear-gradient(135deg, rgba(124, 79, 97, 0.84), rgba(185, 111, 134, 0.70)); }
        .summary-icon.date { background: linear-gradient(135deg, rgba(180, 139, 94, 0.76), rgba(224, 195, 164, 0.62)); }
        .summary-value {
            font-weight: 700;
            color: #3a2330;
            text-align: right;
            flex: 1;
        }
        .summary-row.highlight .summary-value {
            font-size: 1.02rem;
        }
        .card .form-control,
        .card .form-select { background: rgba(255,255,255,0.88); border-color: rgba(185, 111, 134, 0.18); color: #3a2330; }
        .card .form-control:focus,
        .card .form-select:focus { box-shadow: 0 0 0 0.2rem rgba(185, 111, 134, 0.16); border-color: rgba(185, 111, 134, 0.34); }
        .card .btn-dark { background: linear-gradient(135deg, #b96f86, #8f4d62); border-color: #8f4d62; }
        .card .btn-dark:hover { background: linear-gradient(135deg, #c67b93, #9a5770); border-color: #9a5770; }
        .card .btn-outline-secondary { color: #8f4d62; border-color: rgba(185, 111, 134, 0.42); background: rgba(255, 255, 255, 0.58); }
        .card .btn-outline-secondary:hover { color: #fff; background: #b96f86; border-color: #b96f86; }
        .slot-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
        .slot-btn { border: 1px solid rgba(185, 111, 134, 0.45); background: rgba(255,255,255,0.75); color: #6f3d4f; border-radius: 10px; padding: 6px 8px; font-weight: 600; font-size: 0.85rem; }
        .slot-btn.selected { background: linear-gradient(135deg, #b96f86, #8f4d62); color: #fff; border-color: #8f4d62; }
        .slot-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .suggestion-shell { background: rgba(255, 251, 252, 0.82); border: 1px solid rgba(185, 111, 134, 0.22); border-radius: 14px; padding: 0.9rem; }
        .suggestion-title { font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(122, 72, 86, 0.74); margin-bottom: 0.3rem; }
        .suggestion-copy { font-size: 0.9rem; color: #6f3d4f; margin-bottom: 0.75rem; }
        .suggestion-group + .suggestion-group { margin-top: 0.85rem; }
        .suggestion-group-label { font-size: 0.82rem; font-weight: 700; color: #3a2330; margin-bottom: 0.45rem; }
        .suggestion-row { display: flex; flex-wrap: wrap; gap: 8px; }
        .suggestion-chip { border: 1px solid rgba(143, 77, 98, 0.28); background: rgba(255, 255, 255, 0.92); color: #6f3d4f; border-radius: 999px; padding: 0.45rem 0.8rem; font-size: 0.84rem; font-weight: 700; }
        .suggestion-chip:hover { background: rgba(185, 111, 134, 0.14); border-color: rgba(143, 77, 98, 0.4); }

        @media (max-width: 576px) {
            .agendamento-container { padding: 12px; }

            .card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                padding: 1.25rem !important;
            }

            .wizard-steps {
                flex-direction: column;
                align-items: stretch;
            }

            .step {
                width: 100%;
                justify-content: flex-start;
            }

            .step-bar {
                display: none;
            }

            .wizard-actions {
                flex-direction: column;
            }

            .wizard-actions .btn {
                width: 100% !important;
            }

            .summary-row {
                flex-direction: column;
            }

            .summary-label,
            .summary-value {
                text-align: left;
                width: 100%;
            }

            .alert ul {
                padding-left: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <video class="video-bg" autoplay muted loop>
        <source src="/videos/studio2.mp4" type="video/mp4">
        Seu navegador não suporta vídeo em HTML5.
    </video>
    <div class="overlay"></div>
    <div class="agendamento-container">
        <div class="card p-4" style="width:min(92vw, 480px); max-width: 480px; min-width: 0;">
            <h3 class="mb-4 text-center">Agende seu horário</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(isset($setupError))
                <div class="alert alert-warning">{{ $setupError }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="wizard-steps mb-4">
                <div class="step active"><div class="step-indicator">1</div><span>Horário</span></div>
                <div class="step-bar active"></div>
                <div class="step"><div class="step-indicator">2</div><span>Serviço</span></div>
                <div class="step-bar"></div>
                <div class="step"><div class="step-indicator">3</div><span>Profissional</span></div>
                <div class="step-bar"></div>
                <div class="step"><div class="step-indicator">4</div><span>Confirmar</span></div>
            </div>

            <form method="POST" action="{{ route('agendamento.store') }}" id="agendamento-form">
                @csrf

                <div class="wizard-step active" data-step="1">
                    <div class="mb-3">
                        <label for="data" class="form-label">Escolha a data</label>
                        <input type="date" class="form-control" id="data" name="data" value="{{ old('data', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="horario" class="form-label">Escolha o horário</label>
                        <input type="text" class="form-control" id="horario" name="horario" inputmode="numeric" placeholder="00:00" maxlength="5" pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" value="{{ old('horario') }}" required>
                    </div>
                    <button type="button" class="btn btn-dark w-100" id="next-to-service" {{ isset($setupError) ? 'disabled' : '' }}>Próximo</button>
                </div>

                <div class="wizard-step" data-step="2">
                    <div class="mb-3">
                        <label for="servico" class="form-label">Escolha o serviço</label>
                        <select class="form-select" id="servico" name="servico_id" required>
                            <option value="">Selecione...</option>
                            @foreach($servicos as $servico)
                                <option value="{{ $servico->id }}" data-nome="{{ $servico->nome }}" data-valor="{{ number_format($servico->valor, 2, ',', '.') }}" data-duracao="{{ $servico->duracao ? substr($servico->duracao, 0, 5) : '' }}" @selected(old('servico_id') == $servico->id)>
                                    {{ $servico->nome }} - R$ {{ number_format($servico->valor, 2, ',', '.') }}@if($servico->duracao) - {{ substr($servico->duracao, 0, 5) }}@endif
                                </option>
                            @endforeach
                        </select>
                        @if($servicos->isEmpty())
                            <small class="text-danger d-block mt-2">Nenhum serviço cadastrado. Solicite cadastro para o Studio Franciele Cesario.</small>
                        @endif
                    </div>
                    <div class="wizard-actions">
                        <button type="button" class="btn btn-outline-secondary w-50" id="back-to-time">Voltar</button>
                        <button type="button" class="btn btn-dark w-50" id="next-to-professional" {{ isset($setupError) ? 'disabled' : '' }}>Próximo</button>
                    </div>
                </div>

                <div class="wizard-step" data-step="3">
                    <div class="mb-3">
                        <label for="profissional" class="form-label">Escolha a profissional</label>
                        <select class="form-select" id="profissional" name="funcionario_id" required>
                            <option value="">Selecione...</option>
                            @foreach($funcionarios as $funcionario)
                                <option value="{{ $funcionario->id }}" @selected(old('funcionario_id') == $funcionario->id)>
                                    {{ $funcionario->nome }}@if($funcionario->especialidade) - {{ $funcionario->especialidade }}@endif
                                </option>
                            @endforeach
                        </select>
                        @if($funcionarios->isEmpty())
                            <small class="text-danger d-block mt-2">Nenhum profissional cadastrado. Solicite cadastro para o Studio Franciele Cesario.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horarios disponiveis para a data selecionada</label>
                        <div id="slots-loading" class="small text-muted mb-2 d-none">Carregando horarios...</div>
                        <div id="slots-empty" class="small text-muted mb-2">Selecione uma profissional para ver os horarios livres.</div>
                        <div id="slot-grid" class="slot-grid"></div>
                        <div id="slot-suggestions" class="suggestion-shell mt-3 d-none">
                            <div class="suggestion-title">Sugestoes para continuar</div>
                            <div class="suggestion-copy">Se o horario desejado nao estiver disponivel, escolha um dos proximos encaixes livres.</div>
                            <div id="slot-suggestions-body"></div>
                        </div>
                    </div>
                    <div class="wizard-actions">
                        <button type="button" class="btn btn-outline-secondary w-50" id="back-to-service">Voltar</button>
                        <button type="button" class="btn btn-dark w-50" id="review-agendamento" {{ isset($setupError) ? 'disabled' : '' }}>Revisar</button>
                    </div>
                </div>

                <div class="wizard-step" data-step="4">
                    <div class="summary-panel mb-3">
                        <div class="summary-heading">
                            <div class="summary-kicker">Studio Franciele Cesario</div>
                            <div class="summary-title">Confirmação do agendamento</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label"><span class="summary-icon time" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"></circle><path d="M12 8v4l3 2"></path></svg></span>Horário</div>
                            <div class="summary-value" id="summary-horario"></div>
                        </div>
                        <div class="summary-row highlight">
                            <div class="summary-label"><span class="summary-icon service" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 20c4-1 7-4 8-8"></path><path d="M14 4c2 2 3 4 4 6"></path><path d="M8 8l8 8"></path><path d="M14 14l6 6"></path><path d="M10 10l-2 2"></path></svg></span>Serviço</div>
                            <div class="summary-value" id="summary-servico"></div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label"><span class="summary-icon professional" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path><path d="M5 20a7 7 0 0 1 14 0"></path></svg></span>Profissional</div>
                            <div class="summary-value" id="summary-profissional"></div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label"><span class="summary-icon date" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="6" width="16" height="14" rx="2"></rect><path d="M8 4v4"></path><path d="M16 4v4"></path><path d="M4 10h16"></path></svg></span>Data</div>
                            <div class="summary-value" id="summary-data"></div>
                        </div>
                    </div>
                    <p class="mb-3 text-center">Confira os dados e confirme para finalizar o agendamento.</p>
                    <div class="wizard-actions">
                        <button type="button" class="btn btn-outline-secondary w-50" id="back-to-professional">Voltar</button>
                        <button type="submit" class="btn btn-dark w-50" {{ $servicos->isEmpty() || $funcionarios->isEmpty() || isset($setupError) ? 'disabled' : '' }}>Confirmar agendamento</button>
                    </div>
                </div>
            </form>
            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('agendamento.meus') }}" class="btn btn-outline-secondary w-100">Meus agendamentos</a>
                @can('access-admin-area')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-dark w-100">Painel admin</a>
                @endcan
            </div>
        </div>
    </div>
    <script>
        const steps = Array.from(document.querySelectorAll('.wizard-step'));
        const stepLabels = Array.from(document.querySelectorAll('.step'));
        const bars = Array.from(document.querySelectorAll('.step-bar'));
        const horarioField = document.getElementById('horario');
        const servicoField = document.getElementById('servico');
        const profissionalField = document.getElementById('profissional');
        const dataField = document.getElementById('data');

        const summaryHorario = document.getElementById('summary-horario');
        const summaryServico = document.getElementById('summary-servico');
        const summaryProfissional = document.getElementById('summary-profissional');
        const summaryData = document.getElementById('summary-data');
        const slotsLoading = document.getElementById('slots-loading');
        const slotsEmpty = document.getElementById('slots-empty');
        const slotGrid = document.getElementById('slot-grid');
        const slotSuggestions = document.getElementById('slot-suggestions');
        const slotSuggestionsBody = document.getElementById('slot-suggestions-body');
        const agendamentoBloqueado = @json(isset($setupError));
        const initialSuggestions = @json(session('availabilitySuggestions', ['same_day' => [], 'next_days' => []]));
        const shouldResumeSlotStep = @json($errors->has('horario') || session()->has('availabilitySuggestions'));
        let currentAvailableSlots = [];

        function showStep(stepNumber) {
            steps.forEach((step) => {
                step.classList.toggle('active', Number(step.dataset.step) === stepNumber);
            });

            stepLabels.forEach((label, index) => {
                label.classList.toggle('active', index + 1 <= stepNumber);
            });

            bars.forEach((bar, index) => {
                bar.classList.toggle('active', index + 1 < stepNumber);
            });
        }

        function formatHorario(value) {
            const digits = value.replace(/\D/g, '').slice(0, 4);

            if (digits.length <= 2) {
                return digits;
            }

            return `${digits.slice(0, 2)}:${digits.slice(2)}`;
        }

        function formatDate(value) {
            if (!value) {
                return '';
            }

            const parts = value.split('-');
            if (parts.length !== 3) {
                return value;
            }

            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        function hasSuggestions(suggestions) {
            return Boolean((suggestions?.same_day || []).length || (suggestions?.next_days || []).length);
        }

        function buildSlots(disponiveis) {
            currentAvailableSlots = Array.isArray(disponiveis) ? disponiveis : [];
            slotGrid.innerHTML = '';

            if (!currentAvailableSlots.length) {
                slotsEmpty.textContent = 'Nao ha horarios disponiveis para essa data e profissional.';
                slotsEmpty.classList.remove('d-none');
                return;
            }

            slotsEmpty.classList.add('d-none');

            currentAvailableSlots.forEach((horario) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `slot-btn ${horarioField.value === horario ? 'selected' : ''}`;
                button.textContent = horario;
                button.addEventListener('click', () => {
                    horarioField.value = horario;
                    document.querySelectorAll('.slot-btn').forEach((btn) => btn.classList.remove('selected'));
                    button.classList.add('selected');
                    renderSuggestions({ same_day: [], next_days: [] });
                });

                slotGrid.appendChild(button);
            });
        }

        function createSuggestionButton(data, horario) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'suggestion-chip';
            button.textContent = `${formatDate(data)} - ${horario}`;
            button.addEventListener('click', () => {
                dataField.value = data;
                horarioField.value = horario;
                showStep(3);
                loadSlots();
            });

            return button;
        }

        function renderSuggestions(suggestions) {
            slotSuggestionsBody.innerHTML = '';

            if (!hasSuggestions(suggestions)) {
                slotSuggestions.classList.add('d-none');
                return;
            }

            slotSuggestions.classList.remove('d-none');

            if ((suggestions.same_day || []).length) {
                const group = document.createElement('div');
                group.className = 'suggestion-group';

                const label = document.createElement('div');
                label.className = 'suggestion-group-label';
                label.textContent = 'Ainda nessa data';
                group.appendChild(label);

                const row = document.createElement('div');
                row.className = 'suggestion-row';
                suggestions.same_day.forEach((suggestion) => {
                    row.appendChild(createSuggestionButton(suggestion.data, suggestion.horario));
                });
                group.appendChild(row);
                slotSuggestionsBody.appendChild(group);
            }

            (suggestions.next_days || []).forEach((daySuggestion) => {
                const group = document.createElement('div');
                group.className = 'suggestion-group';

                const label = document.createElement('div');
                label.className = 'suggestion-group-label';
                label.textContent = `Proxima data livre: ${formatDate(daySuggestion.data)}`;
                group.appendChild(label);

                const row = document.createElement('div');
                row.className = 'suggestion-row';
                (daySuggestion.horarios || []).forEach((horario) => {
                    row.appendChild(createSuggestionButton(daySuggestion.data, horario));
                });
                group.appendChild(row);
                slotSuggestionsBody.appendChild(group);
            });
        }

        async function loadSlots() {
            if (agendamentoBloqueado) {
                currentAvailableSlots = [];
                slotGrid.innerHTML = '';
                slotsEmpty.textContent = 'Finalize a configuracao da hospedagem para liberar os horarios.';
                slotsEmpty.classList.remove('d-none');
                renderSuggestions({ same_day: [], next_days: [] });
                return;
            }

            if (!dataField.value || !profissionalField.value || !servicoField.value) {
                currentAvailableSlots = [];
                slotsEmpty.textContent = 'Selecione servico e profissional para ver os horarios livres.';
                slotsEmpty.classList.remove('d-none');
                slotGrid.innerHTML = '';
                renderSuggestions({ same_day: [], next_days: [] });
                return;
            }

            slotsLoading.classList.remove('d-none');
            slotsEmpty.classList.add('d-none');

            try {
                const params = new URLSearchParams({
                    data: dataField.value,
                    funcionario_id: profissionalField.value,
                    servico_id: servicoField.value,
                });

                if (horarioField.value) {
                    params.set('horario', horarioField.value);
                }

                const response = await fetch(`{{ route('agendamento.horarios') }}?${params.toString()}`);

                if (!response.ok) {
                    throw new Error('Falha ao carregar horarios');
                }

                const payload = await response.json();
                buildSlots(payload.disponiveis || []);

                if (horarioField.value && !currentAvailableSlots.includes(horarioField.value)) {
                    renderSuggestions(payload.sugestoes || { same_day: [], next_days: [] });
                    slotsEmpty.textContent = 'O horario digitado nao esta disponivel. Escolha um encaixe livre abaixo.';
                    slotsEmpty.classList.remove('d-none');
                } else {
                    renderSuggestions({ same_day: [], next_days: [] });
                }
            } catch (error) {
                currentAvailableSlots = [];
                slotGrid.innerHTML = '';
                slotsEmpty.textContent = 'Nao foi possivel carregar os horarios no momento.';
                slotsEmpty.classList.remove('d-none');
                renderSuggestions({ same_day: [], next_days: [] });
            } finally {
                slotsLoading.classList.add('d-none');
            }
        }

        horarioField.addEventListener('input', () => {
            horarioField.value = formatHorario(horarioField.value);
        });

        horarioField.addEventListener('change', () => {
            if (servicoField.value && profissionalField.value) {
                loadSlots();
            }
        });

        document.getElementById('next-to-service').addEventListener('click', () => {
            if (!horarioField.value) {
                horarioField.reportValidity();
                return;
            }

            if (!dataField.value) {
                dataField.reportValidity();
                return;
            }

            showStep(2);
        });

        document.getElementById('next-to-professional').addEventListener('click', () => {
            if (!servicoField.value) {
                servicoField.reportValidity();
                return;
            }

            showStep(3);
            loadSlots();
        });

        document.getElementById('review-agendamento').addEventListener('click', () => {
            if (!profissionalField.value) {
                profissionalField.reportValidity();
                return;
            }

            if (!currentAvailableSlots.includes(horarioField.value)) {
                slotsEmpty.textContent = 'Esse horario nao esta disponivel para a profissional escolhida. Use uma das sugestoes abaixo.';
                slotsEmpty.classList.remove('d-none');

                if (hasSuggestions(initialSuggestions)) {
                    renderSuggestions(initialSuggestions);
                }

                loadSlots();
                return;
            }

            summaryHorario.textContent = horarioField.value;
            const servicoSelecionado = servicoField.options[servicoField.selectedIndex];
            const nomeServico = servicoSelecionado?.dataset.nome || servicoSelecionado.text;
            const valorServico = servicoSelecionado?.dataset.valor ? ` - R$ ${servicoSelecionado.dataset.valor}` : '';
            const duracaoServico = servicoSelecionado?.dataset.duracao ? ` - ${servicoSelecionado.dataset.duracao}` : '';

            summaryServico.textContent = `${nomeServico}${valorServico}${duracaoServico}`;
            summaryProfissional.textContent = profissionalField.options[profissionalField.selectedIndex].text;
            summaryData.textContent = formatDate(dataField.value);

            showStep(4);
        });

        document.getElementById('back-to-time').addEventListener('click', () => showStep(1));
        document.getElementById('back-to-service').addEventListener('click', () => showStep(2));
        document.getElementById('back-to-professional').addEventListener('click', () => showStep(3));
        servicoField.addEventListener('change', loadSlots);
        profissionalField.addEventListener('change', loadSlots);
        dataField.addEventListener('change', loadSlots);

        if (shouldResumeSlotStep && servicoField.value && profissionalField.value) {
            showStep(3);
            renderSuggestions(initialSuggestions);
            loadSlots();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>