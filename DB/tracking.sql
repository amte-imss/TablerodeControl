 -- nuevo menu
 
drop table if exists sistema.modulos;
drop table if exists sistema.configuradores;

create table sistema.configuradores(
id_configurador serial not null, 
nombre varchar(100) not null, 
descripcion text, 
primary key(id_configurador)
);

create table sistema.modulos(
id_modulo serial not null, 
nombre varchar(100) not null, 
descripcion text,
url varchar(256), 
id_modulo_padre int, 
orden int, 
id_configurador int,
visible boolean not null default true,
activo boolean not null default true, 
primary key(id_modulo), 
foreign key(id_modulo_padre) references sistema.modulos(id_modulo), 
foreign key(id_configurador) references sistema.configuradores(id_configurador)
);

insert into sistema.configuradores(nombre) values ('Menu'), ('Accion');

insert into sistema.modulos(nombre, url, id_modulo_padre, orden, id_configurador) values 
('Administracion', null, null, 3, 1),
('Educación continua', null, null, 2, 1), 
('Herramientas administrativas', null, null, 4, 1), 
('Usuarios', '/administracion/usuarios/', 1, 1, 1), 
('Grupos', '/administracion/usuarios/', 1, 2, 1), 
('Módulos', '/administracion/servicios/', 1, 3, 1), 
('Asignación de usuarios por grupos', '/administracion/grupos_usuarios/', 1, 4, 1), 
('Captcha', '/captcha/', null, 1, null), 
('Hechos', null, 3, 1, 1), 
('Cambia estado de un hecho', '/carga_hechos/update_carga/', 9, 1, 2), 
('Lista Hechos', '/carga_hechos/get_lista/', 1, 1, 1), 
('Nuevo hecho', '/carga_hechos/draw_form/', 1, 1, 1), 
('Catálogos', null, 1, 1, 1), 
('Alineación estrategica', '/catalogos/alineacion_estrategica', 1, 1, 1), 
('Categoría', '/catalogos/categoria', 1, 1, 1), 
('Curso', '/catalogos/curso', 1, 1, 1), 
('Delegación', '/catalogos/delegacion', 1, 1,1 ), 
('Departamento', '/catalogos/departamento', 1, 1, 1), 
('Grupos de categorías', '/catalogos/grupo_categoria', 1,1,1 ), 
('Implementación', '/catalogos/implementacion', 1, 1, 1);
