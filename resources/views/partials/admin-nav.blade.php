<div class="admin-nav p-3 p-lg-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn {{ ($activePage ?? null) === 'dashboard' ? 'btn-dark' : 'btn-outline-dark' }}">Painel</a>
        <a href="{{ route('admin.agendamentos.index') }}" class="btn {{ ($activePage ?? null) === 'agendamentos' ? 'btn-dark' : 'btn-outline-dark' }}">Clientes agendados</a>
        <a href="{{ route('admin.funcionarios.index') }}" class="btn {{ ($activePage ?? null) === 'profissionais' ? 'btn-dark' : 'btn-outline-dark' }}">Profissionais</a>
        <a href="{{ route('admin.servicos.index') }}" class="btn {{ ($activePage ?? null) === 'servicos' ? 'btn-dark' : 'btn-outline-dark' }}">Servicos</a>
        <a href="{{ route('admin.agendamentos.today') }}" class="btn btn-outline-secondary">Agenda do dia</a>
    </div>
</div>