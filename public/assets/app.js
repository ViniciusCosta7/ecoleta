document.addEventListener('DOMContentLoaded', () => {
    const lista = document.getElementById('listaMateriais');
    const botao = document.getElementById('adicionarMaterial');

    if (botao && lista) {
        botao.addEventListener('click', () => {
            const linha = document.createElement('div');
            linha.className = 'linha-material';
            linha.innerHTML = `
                <select name="material_tipo[]" required>
                    <option value="papel">Papel</option>
                    <option value="plastico">Plástico</option>
                    <option value="vidro">Vidro</option>
                    <option value="metal">Metal</option>
                    <option value="eletronico">Eletrônico</option>
                    <option value="outro">Outro</option>
                </select>
                <input type="number" name="material_quantidade[]" min="0.01" step="0.01" required placeholder="Quantidade (kg)">
                <button type="button" class="remover-material" title="Remover material">×</button>`;
            lista.appendChild(linha);
        });

        lista.addEventListener('click', (evento) => {
            if (evento.target.classList.contains('remover-material')) {
                const linhas = lista.querySelectorAll('.linha-material');
                if (linhas.length > 1) evento.target.closest('.linha-material').remove();
            }
        });
    }
});
