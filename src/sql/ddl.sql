delimiter ;

create table if not exists dynamic_routes_available_methods (
    method varchar(10) not null primary key
);

insert ignore into dynamic_routes_available_methods (`method`) values ('GET'),('POST'),('PUT'),('DELETE'),('PATCH');



create table if not exists dynamic_routes (
    id varchar(36) not null primary key,
    `route` varchar(255) not null,
    `routetemplate` longtext not null,
    `checksum` varchar(64) default ''
);


create table if not exists dynamic_routes_methods (
    route_id varchar(36) not null,
    method varchar(10) not null,
    primary key (route_id, method),
    constraint fk_dynamic_routes_methods_route_id
    foreign key (route_id)
    references dynamic_routes(id)
    on delete cascade
    on update cascade
);

