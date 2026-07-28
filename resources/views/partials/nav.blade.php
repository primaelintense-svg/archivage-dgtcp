<div style="background: #333; color: white; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div>
        <strong>Archivage DGTCP</strong>
        &nbsp;—&nbsp;
        Connecté : {{ auth()->user()->prenom }} {{ auth()->user()->nom }} ({{ auth()->user()->role }})
    </div>
    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" style="cursor: pointer;">Se déconnecter</button>
    </form>
</div>
