SELECT cp.id_order,  count(np.id_pe_producto), op.fecha, op.peso_transporte,op.customer_id_group,op.base_factura
       FROM pe_lineas_orders_prestashop cp
       LEFT JOIN pe_lineas_orders_prestashop np ON np.id_order=cp.id_order
        LEFT JOIN pe_orders_prestashop op ON op.pedido=cp.id_order
       WHERE cp.id_order>18696 and cp.id_pe_producto=2276
       GROUP BY cp.id_order,op.fecha,op.peso_transporte,op.customer_id_group,op.base_factura

