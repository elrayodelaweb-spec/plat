<?php
// public/create_store.php - Crea nueva tienda basado en modelo y asigna al usuario
require_once __DIR__ . '/../includes/init.php';
require_login();
$user = current_user($pdo);

if (!empty($user['tenant_id'])) {
    header('Location: /public/dashboard/index.php');
    exit;
}

$template = require __DIR__ . '/../includes/store_template.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF inválido.");

    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $theme = $_POST['theme'] ?? $template['theme'];

    // Inserta tienda
    $stmt = $pdo->prepare("INSERT INTO tenants (name, description, theme, logo_url, created_at) VALUES (:n,:d,:t,:l,NOW())");
    $stmt->execute([
        ':n' => $name ?: $template['name'],
        ':d' => $desc ?: $template['description'],
        ':t' => $theme,
        ':l' => $template['logo_url']
    ]);
    $tenant_id = $pdo->lastInsertId();

    // Vincula usuario
    $upUser = $pdo->prepare("UPDATE users SET tenant_id = :tid WHERE id = :uid");
    $upUser->execute([':tid' => $tenant_id, ':uid' => $user['id']]);

    // Copia configuración
    foreach ($template['settings'] as $k => $v) {
        $set = $pdo->prepare("INSERT INTO tenant_settings (tenant_id, key_name, value, created_at) VALUES (:t,:k,:v,NOW())");
        $set->execute([':t'=>$tenant_id, ':k'=>$k, ':v'=>$v]);
    }
    // Crea productos ejemplo
    foreach ($template['sample_products'] as $p) {
        $prod = $pdo->prepare("INSERT INTO products (tenant_id, title, price, stock, image_url, created_at) VALUES (:t,:title,:price,:stock,:img,NOW())");
        $prod->execute([':t'=>$tenant_id, ':title'=>$p['title'], ':price'=>$p['price'], ':stock'=>$p['stock'], ':img'=>$p['image_url']]);
    }

    header('Location: /public/dashboard/index.php');
    exit;
}

include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h2>Crear tienda</h2>
  <form method="post">
    <?= csrf_field() ?>
    <label>Nombre de la tienda: <input name="name" required></label>
    <label>Descripción: <textarea name="description"></textarea></label>
    <label>Tema:
      <select name="theme">
        <option value="default">Default</option>
        <option value="modern">Moderno</option>
        <option value="classic">Clásico</option>
      </select>
    </label>
    <button type="submit">Crear tienda</button>
  </form>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>
