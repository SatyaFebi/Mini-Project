'use client';

import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar"
import { Package, ShoppingCart, Users, Monitor } from "lucide-react"
import Link from "next/link"
import { usePathname } from "next/navigation"

const items = [
  {
    title: "Point of Sale",
    url: "/pos",
    icon: Monitor,
  },
  {
    title: "Pelanggan",
    url: "/pelanggan",
    icon: Users,
  },
  {
    title: "Barang",
    url: "/barang",
    icon: Package,
  },
  {
    title: "Penjualan",
    url: "/penjualan",
    icon: ShoppingCart,
  },
]

export default function AppSidebar() {
  const pathname = usePathname()

  return (
    <Sidebar>
      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>Menu</SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {items.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton render={<Link href={item.url} />} isActive={pathname === item.url}>
                    <item.icon className="mr-2 h-4 w-4" />
                    <span>{item.title}</span>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
    </Sidebar>
  )
}
